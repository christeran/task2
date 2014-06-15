<?php       
    $account = "";
    if (!empty($_GET['account'])){
        //include twitter api 
        include 'lib/twitter/twitteroauth.php';
        //Twitter token and consumer credentials
        $consumer ="K8Qf9mtPiZx3349F4Lu2Q3KRh";
        $consumersecret="aZ3KnhgD8Eq7E6BaRot5HOf9HmIC4F7G8NFTkmdhz04r4CHalu";
        $accesstoken="132474843-5iH6jLRX0jeklnbFgTTofoiAlNBiyMBoBPLZJ2zD";
        $accesstokensecret="PITwDPbrLmTPq0N1xYJLYQ8Weu5t7PH3LExBug1MXppdP";
    
        $account=$_GET['account'];
        //built a new twitterOAuth  object and make a requests authenticated.
        $twitter = new TwitterOAuth($consumer,$consumersecret,$accesstoken, $accesstokensecret);
        
        /*
         * Users/show
         * @params string url(API) + twiiter account
         * return JSON user information and most recent Tweet
         */
        $user =  $twitter->get("https://api.twitter.com/1.1/users/show.json?screen_name=$account");
        
        /*
         * Statuses/user_timeline
         * @params string url(API) + twiiter account + number of tweets
         * return JSON a collection of the most recent tweetes posted by the user.
         */
        $tweets =  $twitter->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$account&count=5");
    }
?>  
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Twitter Details</title>
        <link rel="SHORTCUT ICON" href="https://abs.twimg.com/favicons/favicon.ico"/>
        <link rel="apple-touch-icon" href="https://abs.twimg.com/favicons/favicon.ico"/>
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" />
        <link href="media/css/bootstrap.css" rel="stylesheet" />
        <link href="media/css/style.css" rel="stylesheet" />
    </head>
    <body>
        <br/>
        <div class="container">            
            <div class="panel panel-primary" style="max-width: 600px; margin-left: auto ; margin-right: auto;">
                <div class="panel-heading">
                    <h3 class="panel-title">Twitter Details</h3>
                </div>
                <div class="panel-body">
                    <?php if (!empty($user->errors)){?>
                        <div class="alert alert-dismissable alert-danger">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Ooppss!</strong> Sorry, this user does not exist.
                        </div>
                    <?php } ?>
                    <form action="index.php" method="get" >
                        <p/>
                        <div class="row">
                            <div class="col-md-6 col-xs-6 col-sm-6">
                                <div class="form-group">
                                    <label for="account" class="control-label">Please enter a twitter account</label>                                                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">@</span>
                                        <input type="text" class="form-control" name="account" placeholder="twitter account" value="<?php echo $account ?>"  maxlength="30" autocomplete="off" onfocus="true">                                
                                    </div>    
                                </div>
                            </div>
                             <div class="col-md-6 col-xs-6 col-sm-6">
                                 <input type="submit" value="Go!" class="btn btn-primary btn" style="margin-top: 25px;"/>                                 
                            </div>
                        </div>  
                        <hr/>
                    </form>
                    <?php if (!empty($user->screen_name)) :?>
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                        <div class="box">
                            <div class="icon">
                                    <image class="image" src="<?php echo $user->profile_image_url?>" />
                                
                                <div class="info">
                                    <h3 class="title"><?php echo $user->name ?></h3>
                                    @<?php echo $user->screen_name?>
                                    <p>
                                        <span class="glyphicon glyphicon-time"></span>Joined <?php echo  date("F Y", strtotime($user->created_at)) ?>
                                        &nbsp;&nbsp;
                                        <span class="glyphicon glyphicon-map-marker"></span><?php echo $user->location?>
                                    </p>       
                                </div>
                             </div>
                        </div>
                         <div class="btn-group btn-group-justified">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary">
                                    <strong>TWEETS</strong><br/>
                                    <?php echo $user->statuses_count?>
                                </button>    
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary">
                                    <strong>FOLLOWING</strong><br/>
                                    <?php echo $user->friends_count?>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary">
                                    <strong>FOLLOWERS</strong><br/>
                                    <?php echo $user->followers_count?>
                                </button>
                            </div>
                        </div> 
                        <br/>
                        <?php if ($user->statuses_count==0){?>
                            <h4>No Tweets were found</h4>
                        <?php }else{ ?>
                            <h4>Last <?php echo ($user->statuses_count>=5)?"5":$user->statuses_count ?> Tweets</h4>
                            <hr/>
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr >
                                        <th class="col-sm-3">
                                            Date
                                        </th>
                                        <th class="col-sm-9">
                                            Message
                                        </th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($tweets as $tweet){    
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo  date("d M Y", strtotime($tweet->created_at)) ?> 
                                        </td>
                                        <td>
                                            <?php echo $tweet->text ?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>    
                    <?php endif; ?>
                </div>            
            </div>
        </div>
        <script src="media/js/bootstrap.min.js"></script> 
    </body>
</html>
