<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Statistic;
    use Exception;
    use GuzzleHttp\Client;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;

    class ValorantApiController extends ResponseController
    {
        private string $region;
        private string $userName;
        private string $tagline;
        private string $filter = "";


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

            $this->region = $request->region;
//            $this->userName = $request->userName;
//            $this->tagline  = $request->tagline;
            $this->filter = $request->filter ? $request->filter : "";

            if ($this->filter == null) {
                $url = "https://api.henrikdev.xyz/valorant/v3/by-puuid/matches/" . $this->region . "/" . $request->recipientPuuId;
            } else {
                $url = "https://api.henrikdev.xyz/valorant/v3/by-puuid/matches/" . $this->region . "/" . $request->recipientId . "/" . $this->filter;

            }
            //return $url;
            $response = Http::withHeaders($this->matchHeader)->get($url);
            //return $response;

            if ($response->successful()) {
                $jsonResponse = json_decode($response);
                //return $jsonResponse;
                $lastMatch = $jsonResponse->data[0];
                $metadata  = $lastMatch->metadata;
                $players   = $lastMatch->players->all_players;
                foreach ($players as $player) {
                    if ($player->puuid == $request->providerPuuId) {
                        return $player;
                    }
                }
                return $players;
                $map          = $metadata->map;
                $gameLenght   = $metadata->game_length;
                $gameStart    = $metadata->game_start;
                $roundsPlayer = $metadata->rounds_player;
                $gameMode     = $metadata->mode;
                $matchId      = $metadata->matchid;
                $region       = $metadata->region;
                $cluster      = $metadata->cluster;
                $queue        = $metadata->queue;

                return $this->apiResponse();
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

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param int                      $id
         *
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            //
        }
    }
