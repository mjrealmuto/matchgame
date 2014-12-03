<?php

	$match_game = "";


	if( isset( $_GET['matchid'] ) )
	{
		
	}
	else
	{
		$match_game = "test";
		$tiles = array(
			"http://www.wtmx.com/images/dj_matching/dj_ericferguson.jpg",
			"http://www.wtmx.com/images/dj_matching/dj_kathyhart.jpg",
			"http://www.wtmx.com/images/dj_matching/dj_melissamcgurren.jpg",	
			"http://www.wtmx.com/images/dj_matching/dj_swany.jpg",
            "http://www.wtmx.com/images/dj_matching/dj_cynthiaskolak.jpg",
            "http://www.wtmx.com/images/dj_matching/dj_whip.jpg",
            "http://www.wtmx.com/images/dj_matching/dj_caracarriveau.jpg",
            "http://www.wtmx.com/images/dj_matching/dj_koz.jpg",
            "http://www.wtmx.com/images/dj_matching/dj_mikeroberts.jpg",
            "http://www.wtmx.com/images/dj_matching/dj_erincarman.jpg"
		);
		
		$coverTile = "http://www.wtmx.com/images/dj_matching/cover_tile.png";
		$useDOB = TRUE;
		$usePhone = TRUE;
		$gameid	= "testgame";
	}

?>
<html>
    <head>
        <title>Match Game - WTMX</title>
        <script type='text/javascript' src='/js/jquery-1.7.1.min.js'></script>
        <style>
            .coverTile{
                z-index: 2;
                position:absolute;
                left: 5px;
                top: 5px;
            }
            .tile{
                z-index:1;
                
            }
            .divBase{
                
            }
            ul.divBase{
                display:block;
                height: 81px;
                padding-bottom: 5px;
                margin-bottom: 5px;
                margin-top: 5px;
            }
            
            ul.divBase li{
                list-style: none;
                position: relative;
                float: left;
                padding: 5px;
                width: 75px;
                height: 81px;
                overflow: hidden;
            }
            
            ul.divBase li img
            {
	            width: 75;
	            height: 81;
            }
            
            .opened
            {
	            left: -300px;
            }
            
            #matchgame
            {
	            width: 500px;
	            height: 400px;
	            overflow: hidden;
            }
            /*
            input
            {
	            border: 2px solid #123456;
	            border-radius: 4px;
	            font-family: arial;
	            font-size: 14px;
	            padding: 5px;
            }
            
            input:focus
            {
	            background-color: yellow;
            }

            #contestEntry
            {
	            margin-left: 40px;
	            padding-left: 10px;
            }
            
            label
            {
	            font-weight: bold;
	            font-family: arial;
            }
            
            .error
            {
            	color: red;
            	font-size: 12px;
            }
            
            #contestEntry
            {
            	display:none;
            }*/
        </style>
        <script type='text/javascript'>
        var divname = "#matchgame";
        var tileArray = new Array(
        <?php
        	$firstTile = TRUE;
        	foreach( $tiles as $tile )
        	{
        		if( $firstTile )
        		{
	        		echo "\"" . $tile . "\"";	
	        		$firstTile = FALSE;	
        		}
        		else
        		{
	        		echo ",\n \"" . $tile . "\"";	
        		}
	        	
        	}
        ?>
        );
        var genericTile = "<?= $coverTile; ?>";
        tileArray = tileArray.concat(tileArray);
        var matched = new Array( );
        var tiles = new Array( );
        var rows = 4;
        var cols = tileArray.length / rows;
        var arrayisFull = false;
        var tiles_index = 0;
        var first_guess = "";
        var first_index = "";
        var second_guess = "";
        var guesses = 0;
        var matches = 0;
        var start_timestamp;
        var end_timestamp;
        var intervalCount = 0;
        var seconds = 0;
        var firstclick = true;
        var total_seconds = 0;
        var minutes = 0;
        var clock_interval;
        var animating = 0;
        var timecompleted = "";
        
        
        $(function( ){
            
            for(var a = 0; a < tileArray.length; a++)
            {
            	var randomNum = Math.floor((Math.random( ) * tileArray.length));
                
                if(a == 0)
                {
                    tiles[randomNum] = tileArray[a];
                }
                else
                {
                    var indexFull = false;
                    while( ! indexFull )
                    {
                        if(typeof tiles[randomNum] == "undefined")
                        {
                            tiles[randomNum] = tileArray[a];
                            indexFull = true;
                        }
                        else
                        {
                            var randomNum = Math.floor((Math.random( ) * tileArray.length));
                        }
                    }    
                }
            }
            
            for( var i = 0 ; i < rows ; i++ ) 
            {
                $(divname).append("<div><ul id='row" + i + "' class='divBase'></ul></div>");
                var thisrow = "#row" + i;
                
                for( var j = 0 ; j < cols ; j++ )
                {
                    $(thisrow).append("<li id='li_" + tiles_index + "'><img src='" + genericTile + "' class='coverTile'/><img src='' class='tile'/></li>");
                    tiles_index++;
                }
            }
            
            flashTiles();
            
            for( t = 1; t < tiles.length; t++ )
            {
            	intervalCount = t;

	            flashTiles( );
            }
            setTimeout("pause", 50000);
                        
             $(divname + " ul li").on({
               mouseover: function( )
               {
                $(this).css("cursor","pointer");    
               },
               mouseout: function( )
               {
                $(this).css("cursor","auto");
               },
               click: function( )
               {
                guess($(this).attr("id")); 
               }
            });
            
            $("form[name=matchform]").submit( function( e ){
	            
	            e.preventDefault( );
	            
	            if( timecompleted != "" )
	            {
	            	var firstname 	= $("input[name=firstname]").val( );
	            	var lastname  	= $("input[name=lastname]").val( );
	            	var email		= $("input[name=emailaddress]").val( );
	            	var address		= $("input[name=streetAddress]").val( );
	            	var city		= $("input[name=city]").val( );
	            	var state		= $("input[name=state]").val( );
	            	var zip			= $("input[name=zip]").val( );
	            	var useDOB		= $("input[name=useDOB]").val( );
	            	var dob_month	= "";
	            	var dob_day		= "";
	            	var dob_year	= "";
	            	var usePhone	= $("input[name=usePhone]").val( );
	            	var area_code	= "";
	            	var f3 			= "";
	            	var l4			= "";
	            
	            	var queryString = "firstname=" + firstname + "&lastname=" + lastname + "&email=" + email + "&address=" + address + "&city=" + city;
	            	queryString += "&state=" + state + "&zip=" + zip + "&game=match&gameid=<?= $gameid ?>&time=" + timecompleted;
	            
	            	if( useDOB == 1 )
	            	{
		            	dob_month 	= $("input[name=dob_month]").val( );
		            	dob_day		= $("input[name=dob_day]").val( );
		            	dob_year	= $("input[name=dob_year]").val( );
		            
		            	queryString += "&dob_month=" + dob_month + "&dob_day=" + dob_day + "&dob_year=" + dob_year;
		        	}
	            
	            	if( usePhone == 1 )
	            	{
		            	area_code 	= $("input[name=ac]").val( );
		            	f3			= $("input[name=f3]").val( );
		            	l4			= $("input[name=l4]").val( );
		            
		            	queryString += "&areacode=" + area_code + "&firstthree=" + f3 + "&lastfour=" + l4;
	            	}
	            	$(".error").html("");
	            	$.ajax({
		            	url 	: "ajax/game.php",
		            	type 	: "POST",
		            	data 	: queryString,
		        		dataType: "text",
		        		success : function( resp ){
			        	
			        		var values = resp.split("|");
			        	
			        		if( values[0] == 0 )
			        		{
				        		values.shift( );
				        	
				        		var path = values[0];
				        		var msg  = values[1];
				        	
				        		if( path == 0 )
				        		{
				        			$("#completion_msg").html( msg );
				        			
				        			$("input[type=text]").val("");
				        		}
				        		else if( path == 1 )
				        		{
				        	
				        			var email 		= values[2];
				        			var old_time 	= values[3];
				        			var time		= values[4];
				        	
				        			resp_bool = confirm( msg );
				        		
				        			if( resp_bool == true )
				        			{
				        				$.ajax({
				        					url		: "ajax/game.php",
				        					type	: "POST",
				        					data	: "game=match_update&gameid=<?= $gameid; ?>&o_time=" + old_time + "&n_time=" + time + "&email=" + email,
				        					dataType: "text",
				        					success	: function( resp )
				        					{
				        						$("#completion_msg").html( "Your Time has been Updated!  Thank you for playing.");
				        						$("input[type=text]").val("");
				        					},
				        					error	: function( ){}
				        				});
				        			}
				        			else
				        			{
				        				$("#completion_msg").html( "Thanks for playing. ");	
				        			}
				        		}
			        		}
			        		else
			        		{
				        		values.shift( );
				        	
				        		for( x in values )
				        		{
					        		var items = values[x].split(",");
					        	
					        		var field = items[0];
					        		var error = items[1];
					        	
					        		$("#" + field + "_error").append( error );
				        		}
				        	}
			        	},
		        		error 	: function( ){}     
		        	});
		        }
		        else
		        {
		        	alert("The Match Game MUST be completed before submission.");
		        
		        }
	         });
        });
        
        function guess( li_id )
        {
            var id_breakdown = li_id.split("_");
            var tile_index = id_breakdown[1];
            
            if( firstclick )
            {
	            clock_interval = setInterval("count( )", 1000);
	            var s = new Date( ); 
	            start_timestamp = s.getTime( );
	            
	            firstclick = 0;
	        }
            
            switch( guesses )
            {
                case 0:
                	if( ! animating )
                	{
	                	$("#" + li_id + " .tile").attr("src",tiles[tile_index]);
	                    first_guess = tiles[tile_index];
	                    first_index = tile_index;
	                    
	                    if( $.inArray( first_guess, matched) == -1 )
	                    {
	                    	$("#" + li_id + " .coverTile").stop( ).animate({left : '-=300px'},{ duration: 1000, queue: true }, function( ){  } );
	
	                    	guesses++;
	                    }	
	                }
                break;
                case 1:
                	guesses = 0;
                	animating = 1;
                	if( parseInt( tile_index ) != parseInt( first_index ) )
                	{
						$("#" + li_id + " .tile").attr("src",tiles[tile_index]);
	                    
	                    second_guess  = tiles[tile_index];
	                    
	                    $("#" + li_id + " .coverTile").stop( ).animate({left : '-=300px'}, 1000 ,function( ){  
		                    
		                    if(first_guess == second_guess){
	                        
		        				matches++;
		        				
		        				matched.push( first_guess );
		                        
		                        if(matches == ( tileArray.length/2 ) )
		                        {
		                            var e = new Date( );
		                            end_timestamp= e.getTime( );
		                            
		                            diff =  end_timestamp - start_timestamp;
		                            
		                            clearInterval(clock_interval);
		                            
		                            var hh = Math.floor( diff / 1000 / 60 / 60 );
		                            
		                            diff -= hh * 1000 * 60 * 60;
		                            
		                            var mm = Math.floor( diff / 1000/ 60 );
		                            
		                            diff -= mm * 1000 * 60;
		                            
		                            var ss = Math.floor( diff/ 1000 );
		                            
		                            completeMSG = "Your Time was: ";
		                            
		                            if( hh > 0 )
		                            {
			                            if( hh == 1 )
			                            {
				                            timecompleted += "1 Hour ";
			                            }
			                            else
			                            {
				                            timecompleted += hh + " Hours ";
			                            }
		                            }
		                            
		                            if( mm > 0 )
		                            {
			                            if( mm == 1 )
			                            {
				                            timecompleted += "1 Minute ";
			                            }
			                            else
			                            {
				                            timecompleted += mm + " Minutes ";
			                            }
		                            }
		                            
		                            timecompleted += ss + " Seconds.";
		                            
		                            completeMSG += timecompleted;
		                            
		                            alert( completeMSG );
		                            
		                            $(divname).append("<div>You WIN!!</div>");
		                            
		                            $("#contestEntry").show( );
		                        }
		                    }else
		                    {
		                    	$("#" + li_id + " .coverTile, #li_" + first_index + " .coverTile ").stop( ).animate({left : '5px'}, { duration: 200});
	
		                    }
		                    
		                    first_index = "";
		                    first_guess = "";
		                    second_guess = "";
		                    animating = 0;
		                });
					}
	                break;
            }
        }
        
        function flashTiles( )
        {
            $("#li_" + intervalCount + " .tile").attr("src",tiles[intervalCount]);
            
            
            $("#li_" + intervalCount + " .coverTile").animate({left : '-=300px'},1000, function( ){
                
            });
            
            $("#li_" + intervalCount + " .coverTile").animate({left : '5px'}, 1000, function( ){
                   $("#li_" + intervalCount + " .tile").attr("src","");
            });
            

        }
        
        function pause( ){}
        
        
        
        function count( )
        {
	        seconds++;
	        total_seconds++;
	        
	    }
        
        </script>
    </head>
    <body>
        <div id='matchgame' ></div>
        <div id='completion_msg'></div>
        <!--<div id='contestEntry'>
        	<form name='matchform' method='post' >
	        	<label for='firstname'>
	        	First Name
	        	</label>
	        	<br />
	        	<input type='text' name='firstname' size='35'/>
	        	<br />
	        	<div id='firstname_error' class='error'></div>
	        	<br />
	        	<label for='lastname'>
	        	Last Name
	        	</label>
	        	<br />
	        	<input type='text' name='lastname' size='35'/>
	        	<br />
	        	<div id='lastname_error' class='error'></div>
	        	<br />
	        	<label for='email'>
	        	E-Mail Address
	        	</label>
	        	<br />
	        	<input type='text' name='emailaddress' size='40' />
	        	<br />
	        	<div id='emailaddress_error' class='error'></div>
	        	<br />
	        	<label for='streetAddress'>
	        	Street Address
	        	</label>
	        	<br />
	        	<input type='text' name='streetAddress' size='40'/>
	        	<br />
	        	<div id='streetaddress_error' class='error'></div>
	        	<br />
	        	<table>
	        		<tr>
			        	<td>
				        	<label for='city'>
				        	City
				        	</label>
				        	<br />
				        	<input type='text' name='city' />,
			        	</td>
			        	<td>
				        	<label for='state'>
					        State
					        </label>
					        <br />
					        <input type='text' name='state' size='2'/>
			        	</td>
			        	<td> 
				        	<label for='zip'>
				        	Zip
				        	</label>
				        	<br />
				        	<input type='text' name='zip' size='5' />
				        </td>
	        		</tr>
	        		<tr>
	        			<td>
	        				<div id='city_error' class='error'></div>
	        			</td>
	        			<td>
	        				<div id='state_error' class='error'></div>
	        			</td>
	        			<td>
	        				<div id='zip_error' class='error'></div>
	        			</td>
	        		</tr>
	        	</table>
	        	<br />
	        	
	        	<?php
	        	if( $useDOB )
	        	{
		        	echo "<input type='hidden' name='useDOB' value='1' />\n";
		        	echo "<label for='dob'>";
		        	echo "Date of Birth";
		        	echo "</label>";
		        	echo "<br />";
		        	echo "<input type='text' name='dob_month' size='2' />";
		        	echo "&nbsp;";
		        	echo "<input type='text' name='dob_day' size='2' />";
		        	echo "&nbsp;";
		        	echo "<input type='text' name='dob_year' size='4' />";
		        	echo "<br />";
		        	echo "<div id='dob_error' class='error'></div>";
		        	echo "<br />";
	        	}
	        	else
	        	{
		        	echo "<input type='hidden' name='useDOB' value='0' />\n";
	        	}
	        	
	        	if( $usePhone )
	        	{
		        	echo "<input type='hidden' name='usePhone' value='1' />\n";
		        	echo "<label for='ac'>";
		        	echo "Phone";
		        	echo "</label>";
		        	echo "<br />";
		        	echo "<input type='text' name='ac' size='3' />\n";
		        	echo "-";
		        	echo "<input type='text' name='f3' size='3' />\n";
		        	echo "-";
		        	echo "<input type='text' name='l4' size='4' />\n";
		        	echo "<br />";
		        	echo "<div id='phone_error' class='error'></div>";
		        	echo "<br />";
		        }
	        	else
	        	{
		        	echo "<input type='hidden' name='usePhone' value='0' />\n";
	        	}
	        	
	        	?>
	        	<input type='submit' name='sub1' value=' SUBMIT ' />
        	</form>
        
        </div>-->
    </body>
</html>