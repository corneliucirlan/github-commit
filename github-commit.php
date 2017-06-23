<?php

    class GithubLastCommit
    {
        private $username;
        private $clientId;
        private $clientSecret;

        function __construct($username, $clientId, $clientSecret)
        {
            $this->username     = $username;
            $this->clientId     = $clientId;
            $this->clientSecret = $clientSecret;
        }

        public function getLastCommit()
        {
            $latestRepo = self::getLatestRepo($this->username);
            $commits = self::getCommits($latestRepo, $this->username);

            return array(
                'repo'      => $latestRepo,
                'commit'    => $commits[0]
            );
        }

        private function get_json($url)
        {
            $base = "https://api.github.com/";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $base . $url . '?client_id='.$this->clientId.'&client_secret='.$this->clientSecret);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_USERAGENT, $this->username);

            $content = curl_exec($curl);

            curl_close($curl);

            return $content;
        }

        private function getLatestRepo($user)
        {
            // Get the json from github for the repos
            $json = json_decode(self::get_json("users/$user/repos"), true);

            // Sort the array returend by pushed_at time
            function compare_pushed_at($b, $a)
            {
                return strnatcmp($a['pushed_at'], $b['pushed_at']);
            }

            usort($json, 'compare_pushed_at');

            //Now just get the latest repo
            $json = $json[0];

            return $json;
        }

        function getCommits($repo, $user)
        {
            // Get the name of the repo that we'll use in the request url
            $repoName = $repo["name"];
            return json_decode(self::get_json("repos/$user/$repoName/commits"),true);
        }

    } // end class

?>
