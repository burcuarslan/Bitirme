<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Statistic;
    use Exception;
    use GuzzleHttp\Client;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;
    use PhpParser\Node\Expr\Cast\Object_;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use winTypes;

    class ValorantApiController extends ResponseController
    {
        private string $recipientPuuId;
        private string $region;
        private string $userName;
        private string $tagline;
        private string $filter = "";
        private string $team;
        const WIN  = "Win";
        const LOSE = "Lose";
        const DROW = "Drow";

        private array $userDetailHeader = [
            'authority'          => 'api.henrikdev.xyz',
            'accept'             => '*/*',
            'accept-language'    => 'tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
            'cache-control'      => 'no-cache',
            'origin'             => 'https://docs.henrikdev.xyz',
            'pragma'             => 'no-cache',
            'referer'            => 'https://docs.henrikdev.xyz/',
            'sec-ch-ua'          => '"Not?A_Brand";v="8", "Chromium";v="108", "Google Chrome";v="108"',
            'sec-ch-ua-mobile'   => '?0',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-dest'     => 'empty',
            'sec-fetch-mode'     => 'cors',
            'sec-fetch-site'     => 'same-site',
            'user-agent'         => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
            'Accept-Encoding'    => 'gzip',
        ];

        private array $matchHeader = [
            'authority'          => 'api.henrikdev.xyz',
            'accept'             => '*/*',
            'accept-language'    => 'tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
            'cache-control'      => 'no-cache',
            'origin'             => 'https://docs.henrikdev.xyz',
            'pragma'             => 'no-cache',
            'referer'            => 'https://docs.henrikdev.xyz/',
            'sec-ch-ua'          => '"Not?A_Brand";v="8", "Chromium";v="108", "Google Chrome";v="108"',
            'sec-ch-ua-mobile'   => '?0',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-dest'     => 'empty',
            'sec-fetch-mode'     => 'cors',
            'sec-fetch-site'     => 'same-site',
            'user-agent'         => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
            'Accept-Encoding'    => 'gzip',
        ];

        public function getMatches(Request $request)
        {

            try {
                $jwt = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return $this->apiResponse(null, $e->getMessage(), 400);
            }


            $this->region         = $jwt->region;
            $this->recipientPuuId = $jwt->puuId;
            $this->userName       = $jwt->userName;
            $this->tagline        = $jwt->tagline;
//            $this->userName = $request->userName;
//            $this->tagline  = $request->tagline;
            $this->filter = $request->filter ? $request->filter : "";

            if ($this->filter == null) {
                $url = "https://api.henrikdev.xyz/valorant/v3/matches/" . $this->region . "/" . $this->userName . "/" . $this->tagline;
            } else {
                $url = "https://api.henrikdev.xyz/valorant/v3/matches/" . $this->region . "/" . $this->userName . "/" . $this->tagline. "/" . $this->filter;

            }
            //return $url;
            $response = Http::withHeaders($this->matchHeader)->get($url);
//            return $response;

            $detail = [];
            if ($response->successful()) {
                $jsonResponse = json_decode($response);
                //return $jsonResponse;
                if ($jsonResponse->data == null) {
                    return $this->apiResponse([], "No data found", 200);
                }
                $lastMatch = $jsonResponse->data[1];
                //return $lastMatch;
//                return $jsonResponse->data;
                $metadata = $lastMatch->metadata;
                $players  = $lastMatch->players->all_players;
                $teams    = $lastMatch->teams;


                for ($i = 0; $i < count($jsonResponse->data); $i++) {

                    $detail[$i]['matchId'] = $jsonResponse->data[$i]->metadata->matchid;
                    $detail[$i]['map']     = $jsonResponse->data[$i]->metadata->map;
                    $detail[$i]['mode']    = $jsonResponse->data[$i]->metadata->mode;
                    $detail[$i]['region']  = $jsonResponse->data[$i]->metadata->region;

                    $detail[$i]['cluster'] = $jsonResponse->data[$i]->metadata->cluster;
                    $detail[$i]['region']  = $jsonResponse->data[$i]->metadata->region;
                    foreach ($jsonResponse->data[$i]->players->all_players as $player) {
                        if ($player->puuid == $this->recipientPuuId) {
                            $detail[$i]['findUser'] = $player;
                        }
                    }
                    if ($detail[$i]['findUser']->team == "Blue") {
                        if ($jsonResponse->data[$i]->teams->blue->has_won) {
                            $detail[$i]['wonRound']  = $jsonResponse->data[$i]->teams->blue->rounds_won;
                            $detail[$i]['lostRound'] = $jsonResponse->data[$i]->teams->blue->rounds_lost;
                            $detail[$i]['win']       = self::WIN;

                        } else {
                            $detail[$i]['wonRound']  = $jsonResponse->data[$i]->teams->blue->rounds_won;
                            $detail[$i]['lostRound'] = $jsonResponse->data[$i]->teams->blue->rounds_lost;
                            $detail[$i]['win']       = self::LOSE;
                        }

                    }  elseif ($detail[$i]['findUser']->team == "Red") {
                        if ($jsonResponse->data[$i]->teams->red->has_won) {
                            $detail[$i]['wonRound']  = $jsonResponse->data[$i]->teams->red->rounds_won;
                            $detail[$i]['lostRound'] = $jsonResponse->data[$i]->teams->red->rounds_lost;
                            $detail[$i]['win']       = self::WIN;

                        } else {
                            $detail[$i]['wonRound']  = $jsonResponse->data[$i]->teams->red->rounds_won;
                            $detail[$i]['lostRound'] = $jsonResponse->data[$i]->teams->red->rounds_lost;
                            $detail[$i]['win']       = self::LOSE;
                        }

                    }else {
//                        return $this->apiResponse($jsonResponse->data[$i], "zaaaaaaaa", 200);
                        $detail[$i]['win']       = self::DROW;
                        $detail[$i]['wonRound']  = $jsonResponse->data[$i]->teams->red->rounds_won;
                        $detail[$i]['lostRound'] = $jsonResponse->data[$i]->teams->red->rounds_lost;
                    }
                }
                return $this->apiResponse($detail, "zaaaaaaaa", 200);
                //return $players;
//                $findUser=null;
//                foreach ($players as $player) {
//                    if ($player->puuid == $request->providerPuuId) {
//                        $findUser   = $player;
////                        $this->team = $player->team;
//
//                    }
//                }
//                return $findUser;
//
//

//                $map        = $metadata->map;
//                $gameLenght = $metadata->game_length;
//                $gameStart  = $metadata->game_start;
//                //$roundsPlayer = $metadata->rounds_player;
//                $gameMode = $metadata->mode;
//                $matchId  = $metadata->matchid;
//                $region   = $metadata->region;
//                $cluster  = $metadata->cluster;
//                $queue    = $metadata->queue;


//                $detail = array(
//                    [
//                        'map'        => $map,
//                        'gameLenght' => $gameLenght,
//                        'gameStart'  => $gameStart,
//                        'gameMode'   => $gameMode,
//                        'matchId'    => $matchId,
//                        'region'     => $region,
//                        'cluster'    => $cluster,
//                        'queue'      => $queue,
//                        'findUser'   => $findUser,
//                        'teams'      => $teams,
//                        'win'        => $win,
//                        'wonRound'   => $wonRound,
//                        'lostRound'  => $lostRound,
//
//                    ]
//                );
                return $this->apiResponse($detail, 'User Detail', 200);
            } else {
                return $this->apiResponse($response->body(), 'Failed to get matches.', 400);
            }


        }

        public function getByPuuId($request)
        {
            $userName = $request->userName;
            $tagline  = $request->tagline;
            $url      = "https://api.henrikdev.xyz/valorant/v1/account/" . $userName . "/" . $tagline;
            $response = Http::withHeaders($this->userDetailHeader)->get($url);
            if ($response->successful()) {
                $jsonResponse = json_decode($response);
                $puuId        = $jsonResponse->data->puuid;
                return $puuId;
            } else {
                return throw new Exception("User Not Found!");
            }
        }

        public function getUserDetail($request)
        {
            $this->userName = $request->userName;
            $this->tagline  = $request->tagline;
            $this->region   = $request->region;

            $url      = 'https://api.henrikdev.xyz/valorant/v1/mmr/' . $this->region . '/' . $this->userName . '/' . $this->tagline;
            $response = Http::withHeaders($this->userDetailHeader)->get($url);
            if ($response->successful()) {
                $jsonResponse                   = json_decode($response);
                $userDetail                     = $jsonResponse->data;
                $statistic                      = new Statistic();
                $statistic->userId              = $request->id;
                $statistic->rank                = $userDetail->currenttierpatched;
                $statistic->currentTier         = $userDetail->currenttier;
                $statistic->rankingInTier       = $userDetail->ranking_in_tier;
                $statistic->mmrChangeToLastGame = $userDetail->mmr_change_to_last_game;
                $statistic->elo                 = $userDetail->elo;
                $statistic->old                 = $userDetail->old;
                $isSuccess                      = $statistic->save();
                if ($isSuccess) {
                    return $this->apiResponse($statistic, 'User detail saved successfully.', 200);
                } else {
                    return throw new Exception('User detail could not be saved.');
                }


                //return $jsonResponse;
            }

        }


    }
