<?php 


            // echo $_SERVER['REMOTE_ADDR'];
            // die();

    //  function browser_ip_details(){


            function getUserIP()
                        {
                            // Get real visitor IP behind CloudFlare network
                            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                                    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                                    $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                            }
                            $client  = @$_SERVER['HTTP_CLIENT_IP'];
                            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
                            $remote  = $_SERVER['REMOTE_ADDR'];

                            if(filter_var($client, FILTER_VALIDATE_IP))
                            {
                                $ip = $client;
                            }
                            elseif(filter_var($forward, FILTER_VALIDATE_IP))
                            {
                                $ip = $forward;
                            }
                            else
                            {
                                $ip = $remote;
                            }

                            return $ip;
                        }


                        $user_ip = getUserIP();

                        // echo $user_ip; // Output IP address [Ex: 177.87.193.134]
                        // die();


        // function get_client_ip() {
        //             $ipaddress = '';
        //             if (getenv('HTTP_CLIENT_IP'))
        //                 $ipaddress = getenv('HTTP_CLIENT_IP');
        //             else if(getenv('HTTP_X_FORWARDED_FOR'))
        //                 $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        //             else if(getenv('HTTP_X_FORWARDED'))
        //                 $ipaddress = getenv('HTTP_X_FORWARDED');
        //             else if(getenv('HTTP_FORWARDED_FOR'))
        //                 $ipaddress = getenv('HTTP_FORWARDED_FOR');
        //             else if(getenv('HTTP_FORWARDED'))
        //             $ipaddress = getenv('HTTP_FORWARDED');
        //             else if(getenv('REMOTE_ADDR'))
        //                 $ipaddress = getenv('REMOTE_ADDR');
        //             else
        //                 $ipaddress = 'UNKNOWN';
        //             return $ipaddress;
        //             }

                    // print_r($ipaddress);die();

        //  function get_browserdetails()
        //       {
        //         $u_agent = $_SERVER['HTTP_USER_AGENT'];
        //         if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        //         {
        //             $bname = 'Internet Explorer';
        //             $ub = "MSIE";
        //             }
        //             elseif(preg_match('/Firefox/i',$u_agent))
        //             {
        //                 $bname = 'Mozilla Firefox';
        //                 $ub = "Firefox";
        //             }
        //             elseif(preg_match('/Chrome/i',$u_agent))
        //             {
        //                 $bname = 'Google Chrome';
        //                 $ub = "Chrome";
        //             }
        //             elseif(preg_match('/Safari/i',$u_agent))
        //             {
        //                 $bname = 'Apple Safari';
        //                 $ub = "Safari";
        //             }
        //             elseif(preg_match('/Opera/i',$u_agent))
        //             {
        //                 $bname = 'Opera';
        //                 $ub = "Opera";
        //             }
        //             elseif(preg_match('/Netscape/i',$u_agent))
        //             {
        //                 $bname = 'Netscape';
        //                 $ub = "Netscape";
        //             }

        //     }




        // echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

        // $browser = get_browser(null, true);
        // print_r($browser); die();



        function getBrowser() { 
            $u_agent = $_SERVER['HTTP_USER_AGENT'];
            $bname = 'Unknown';
            $platform = 'Unknown';
            $version= "";
          
            //First get the platform?
            if (preg_match('/linux/i', $u_agent)) {
              $platform = 'linux';
            }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
              $platform = 'mac';
            }elseif (preg_match('/windows|win32/i', $u_agent)) {
              $platform = 'windows';
            }
          
            // Next get the name of the useragent yes seperately and for good reason
            if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
              $bname = 'Internet Explorer';
              $ub = "MSIE";
            }elseif(preg_match('/Firefox/i',$u_agent)){
              $bname = 'Mozilla Firefox';
              $ub = "Firefox";
            }elseif(preg_match('/OPR/i',$u_agent)){
              $bname = 'Opera';
              $ub = "Opera";
            }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
              $bname = 'Google Chrome';
              $ub = "Chrome";
            }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
              $bname = 'Apple Safari';
              $ub = "Safari";
            }elseif(preg_match('/Netscape/i',$u_agent)){
              $bname = 'Netscape';
              $ub = "Netscape";
            }elseif(preg_match('/Edge/i',$u_agent)){
              $bname = 'Edge';
              $ub = "Edge";
            }elseif(preg_match('/Trident/i',$u_agent)){
              $bname = 'Internet Explorer';
              $ub = "MSIE";
            }
          
            // finally get the correct version number
            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) .
          ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern, $u_agent, $matches)) {
              // we have no matching number just continue
            }
            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
              //we will have two since we are not using 'other' argument yet
              //see if version is before or after the name
              if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                  $version= $matches['version'][0];
              }else {
                  $version= $matches['version'][1];
              }
            }else {
              $version= $matches['version'][0];
            }
          
            // check if we have a number
            if ($version==null || $version=="") {$version="?";}
          
            return array(
              'userAgent' => $u_agent,
              'name'      => $bname,
              'version'   => $version,
              'platform'  => $platform,
              'ipaddress' =>  getUserIP()
            );

            $ua=getBrowser();
            $yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'];// . " reports: <br >" . $ua['userAgent'];
            // print_r($yourbrowser);
          } 
          
          // now try it
         

          
        // }
// 
        //   print_r($yourbrowser); die();
            
            
            
?>
