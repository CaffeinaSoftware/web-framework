<?php

	require_once("../../server/bootstrap.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>
<meta charset="utf-8"/>
<title>POS</title>


<link type="text/css" rel="stylesheet" href="../css/f.css"/>

<head>
<body class="safari4 mac Locale_en_US">
<input type="hidden" autocomplete="off" id="post_form_id" name="post_form_id" value="d8f38124ed9e31ef3947198c6d26bff1"/>
<div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;">
</div>
<div class="devsitePage">
	<div class="menu">
		<div class="content">
			<a class="logo" href="/"><img class="img" src="https://s-static.ak.facebook.com/rsrc.php/v1/yW/r/N2f0JA5UPFU.png" alt="Facebook Developers" width="166" height="17"/></a><a class="l" href="/docs/">Documentation</a><a class="l" href="/support/">Support</a><a class="l" href="/blog/">Blog</a><a class="l" href="https://developers.facebook.com/apps">Apps</a>
			<div class="search">
				<form method="get" action="/search">
					<div class="uiTypeahead" id="u272751_1">
						<div class="wrap">
							<input type="hidden" autocomplete="off" class="hiddenInput" name="path" value=""/>
							<div class="innerWrap">
								<span class="uiSearchInput textInput"><span><input type="text" class="inputtext DOMControl_placeholder" name="selection" placeholder="Search Documentation / Apps" autocomplete="off" onfocus="return wait_for_load(this, event, function() &#123;window.ArbiterMonitor &amp;&amp; ArbiterMonitor.pause();;JSCC.get(&#039;j4e77526729eb92f593566592&#039;).init([&quot;submitOnSelect&quot;]);;;window.ArbiterMonitor &amp;&amp; ArbiterMonitor.resume();;&#125;);" spellcheck="false" value="Search Documentation / Apps" title="Search Documentation / Apps"/><button type="submit" title="Search Documentation / Apps"><span class="hidden_elem">Search Documentation / Apps</span></button></span></span>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="clear">
			</div>
		</div>
	</div>
	<div class="body nav">
		<div class="content">
			<div id="bodyMenu" class="bodyMenu">
				<div class="toplevelnav">
					<ul>

						<?php
								$query = mysql_query("select * from clasificacion order by nombre;");
								
								while( ($row = mysql_fetch_assoc( $query )) != null )
								{
									if(isset($_GET["cat"]) && ($_GET["cat"] == $row["id_clasificacion"]) ){
										?>
										<li class="active withsubsections">
										<a class="selected" href="api_doc.php?cat=<?php echo $row["id_clasificacion"]; ?>">
										<div class="navSectionTitle">
											<?php echo $row["nombre"]; ?>
										</div>
										</a>
										<ul class="subsections">
											
										<?php
										$argsq = mysql_query("select * from metodo where id_clasificacion = ". $row["id_clasificacion"] .";");

										while(($m = mysql_fetch_assoc($argsq)) != null)
										{
												
												$n = str_replace("api/", "", $m["nombre"] );
												echo '<li><a href="?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'">' . $n .  '</a></li>';
										}
										?>
										</ul>
										</li>
										<?php

									}else{

										?>
										<li>
										<a href="api_doc.php?cat=<?php echo $row["id_clasificacion"]; ?>">
											<div class="navSectionTitle">
											<?php echo $row["nombre"]; ?>
											</div>
										</a>
										</li>
										<?php	
									}

								}
						?>

						
					</ul>
				</div>

				<ul id="navsubsectionpages">
					
				</ul>
			</div>
			<div id="bodyText" class="bodyText">
				<div class="header">
					<div class="content">
						<?php
							if(isset($_GET["m"])){
								$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);
								echo "<h1>" . $metodo["nombre"] . "</h1>";

							}else if(isset($_GET["cat"])){
								$res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);
								echo "<h1>" . $metodo["nombre"] . "</h1>";
							}
						?>
						
						<div class="breadcrumbs">
							<a href=".">POS ERP</a> 
							<?php
							if(isset($_GET["cat"])){
								$res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);
								//echo "<h1>" . $metodo["nombre"] . "</h1>";
								echo'&rsaquo; <a href=".">'  . $metodo["nombre"] .  '</a>';
							}
							?>
							
						</div>
					</div>
				</div>
				<p>
					
				</p>
				<p>
					<?php
					if(isset($_GET["m"])){
						$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
						$metodo = mysql_fetch_assoc($res);
						echo   $metodo["descripcion"] ;

					}
					?>
				</p>

				<p>
					Alternatively, people and pages with usernames can be accessed using their username as an ID. Since "platform" is the username for the page above, <a href="https://graph.facebook.com/platform">https://graph.facebook.com/platform</a> will return what you expect. All responses are JSON objects.
				</p>


				<?php
					if(isset($_GET["m"])){
						$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
						$metodo = mysql_fetch_assoc($res);
						
						?>

						<h2>Ejemplo peticion</h2>
						<pre><code><?php echo $metodo["ejemplo_peticion"]; ?></code></pre>

						<?php

					}
				?>
				

				<?php
					if(isset($_GET["m"])){
						$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
						$metodo = mysql_fetch_assoc($res);
						
						?>

						<h2>Ejemplo respuesta</h2>
						<pre><code><?php echo $metodo["ejemplo_respuesta"]; ?></code></pre>

						<?php

					}
				?>
				
				<table class="methods" style="margin-left:auto; margin-right:auto">
				<tr>
					<th>
						Method
					</th>
					<th>
						Description
					</th>
					<th>
						Arguments
					</th>
				</tr>
				<tr>
					<td class="method">
						<code>/PROFILE_ID/feed</code>
					</td>
					<td class="desc">
						Publish a new <a href="/docs/reference/api/post">post</a> on the given profile's feed/wall
					</td>
					<td class="args">
						<code>message</code>, <code>picture</code>, <code>link</code>, <code>name</code>, <code>caption</code>, <code>description</code>, <code>source</code>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/OBJECT_ID/comments</code>
					</td>
					<td class="desc">
						Comment on the given object (if it has a <code>/comments</code> connection)
					</td>
					<td class="args">
						<code>message</code>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/OBJECT_ID/likes</code>
					</td>
					<td class="desc">
						Like the given object (if it has a <code>/likes</code> connection)
					</td>
					<td class="args">
						<i>none</i>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/PROFILE_ID/notes</code>
					</td>
					<td class="desc">
						Publish a <a href="/docs/reference/api/note">note</a> on the given profile
					</td>
					<td class="args">
						<code>message</code>, <code>subject</code>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/PROFILE_ID/links</code>
					</td>
					<td class="desc">
						Publish a <a href="/docs/reference/api/link">link</a> on the given profile
					</td>
					<td class="args">
						<code>link</code>, <code>message</code>, <code>picture</code>, <code>name</code>, <code>caption</code>, <code>description</code>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/PROFILE_ID/events</code>
					</td>
					<td class="desc">
						Create an <a href="/docs/reference/api/event">event</a>
					</td>
					<td class="args">
						<code>name</code>, <code>start_time</code>, <code>end_time</code>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/EVENT_ID/attending</code>
					</td>
					<td class="desc">
						RSVP "attending" to the given <a href="/docs/reference/api/event">event</a>
					</td>
					<td class="args">
						<i>none</i>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/EVENT_ID/maybe</code>
					</td>
					<td class="desc">
						RSVP "maybe" to the given <a href="/docs/reference/api/event">event</a>
					</td>
					<td class="args">
						<i>none</i>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/EVENT_ID/declined</code>
					</td>
					<td class="desc">
						RSVP "declined" to the given <a href="/docs/reference/api/event">event</a>
					</td>
					<td class="args">
						<i>none</i>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/PROFILE_ID/albums</code>
					</td>
					<td class="desc">
						Create an <a href="/docs/reference/api/album">album</a>
					</td>
					<td class="args">
						<code>name</code>, <code>message</code>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/ALBUM_ID/photos</code>
					</td>
					<td class="desc">
						Upload a <a href="/docs/reference/api/photo">photo</a> to an <a href="/docs/reference/api/album">album</a>
					</td>
					<td class="args">
						<code>message</code>, <code>source</code><i>(multipart/form-data)</i>
					</td>
				</tr>
				<tr>
					<td class="method">
						<code>/PROFILE_ID/checkins</code>
					</td>
					<td class="desc">
						Create a <a href="/docs/reference/api/checkin">checkin</a> at a location represented by a <a href="/docs/reference/api/page">Page</a>
					</td>
					<td class="args">
						<code>coordinates</code>, <code>place</code>, <code>message</code>, <code>tags</code>
					</td>
				</tr>
				</table>
				<p>
					<a name="deleting"></a>
				</p>
				<hr/>
				<h2>Deleting</h2>
				<p>
					You can delete objects in the graph by issuing HTTP <code>DELETE</code> requests to the object URLs, i.e,
				</p>
				<pre>
					<code>DELETE https://graph.facebook.com/ID?access_token=... HTTP/1.1 </code>
				</pre>
				<p>
					To support clients that do not support all HTTP methods (like JavaScript clients), you can alternatively issue a <code>POST</code> request to an object URL with the additional argument <code>method=delete</code> to override the HTTP method. For example, you can delete a comment by issuing a <code>POST</code> request to <code>https://graph.facebook.com/COMMENT_ID?method=delete</code>.
				</p>
				<p>
					You can delete a like by issuing a <code>DELETE</code> request to <code>/OBJECT_ID/likes</code> (since likes don't have an ID).
				</p>
				<p>
					<a name="analytics"></a>
				</p>
				<h2>Analytics</h2>
				<p>
					When you <a href="/setup/">register your app</a>, you can get detailed analytics about the demographics of your users and how users are sharing from your application with <a href="https://www.facebook.com/insights/">Insights</a>.
				</p>
				<p>
					The Graph API provides programmatic access to all of this data so you can integrate Platform data into your own, custom analytics systems.
				</p>
				<p>
					To download Insights data, you first need to obtain an <a href="/docs/authentication/#app-login">app access token</a>.
				</p>
				<p>
					Once you have your application access token, you can download analytics data for your application at:
				</p>
				<pre>
					https://graph.facebook.com/<b>app_id</b>/insights?access_token=...
				</pre>
				<p>
					That URL outputs all of the analytics data available via the API, including the total number of users, number of active users, and <a href="https://developers.facebook.com/docs/reference/fql/insights">a number of other</a> detailed metrics. For example, you can get the number of impressions of your app's canvas page:
				</p>
				<pre>
					https://graph.facebook.com/<b>app_id</b>/insights/application_canvas_views/day?access_token=...
				</pre>
				<p>
					You can use <code>since</code> and <code>until</code> to specify the time range for which you want data. Both arguments accept times in almost any valid date format:
				</p>
				<pre>
					https://graph.facebook.com/<b>app_id</b>/insights?access_token=...&since=<b>yesterday</b>
				</pre>
				<p>
					Explore the <a href="https://www.facebook.com/insights/">Insights</a> product, the base <code>/insights</code> URL, and the <a href="https://developers.facebook.com/docs/insights/">Insights documentation</a> for more information.
				</p>
				<h2>Batch Requests</h2>
				<p>
					If your app needs the ability to access significant amounts of data or make changes to several objects at once, it is more efficient to combine these operations than to make multiple HTTP requests.
				</p>
				<p>
					To batch requests, please refer to our <a href="https://developers.facebook.com/docs/api/batch/">documentation</a>.
				</p>
				<ul>
					<li><a href="#concepts">Concepts</a></li>
					<li><a href="#objects">Objects</a></li>
				</ul>
				<hr/>
				<h2 id="concepts">Concepts</h2>
				<div class="refindex">
					<div class="page">
						<div class="title">
							<a href="https://developers.facebook.com/docs/reference/api/batch/">Batch Requests</a>
						</div>
						<div class="snippet">
							<p>
								If your application needs the ability to access significant amounts of data in a single go - or you need to make changes to several objects at once, it is often more efficient batch your queries rather than make multiple individual HTTP requests. To enable this, the Graph API support Batching. Batching allows you to pass instructions for several operations in a single HTTP request.
							</p>
						</div>
					</div>
					<div class="page">
						<div class="title">
							<a href="https://developers.facebook.com/docs/reference/api/permissions/">Permissions</a>
						</div>
						<div class="snippet">
							<p>
								Permissions to access GRAPH API fields and connections.
							</p>
						</div>
					</div>
					<div class="page">
						<div class="title">
							<a href="https://developers.facebook.com/docs/reference/api/realtime/">Real-time Updates</a>
						</div>
						<div class="snippet">
							<p>
								The <a href="./">Graph API</a> supports real-time updates to enable your application using Facebook to subscribe to changes in data from Facebook. Your application caches data and receives updates, rather than polling Facebook’s servers. Caching data and using this API can improve the reliability of your application and decrease its load times.
							</p>
						</div>
					</div>
				</div>
				<hr/>

				<div class="mtm pvm uiBoxWhite topborder">
					<div class="mbm">
						<fb:like href="http://developers.facebook.com/docs/reference/api/" send="true" show_faces="false"></fb:like>
					</div>
					<abbr title="Monday, September 5, 2011 at 8:28pm" data-date="Mon, 05 Sep 2011 18:28:49 -0700" class="timestamp">Updated about a week ago</abbr>
				</div>
			</div>
			<div class="clear">
			</div>
		</div>
	</div>
	<div class="footer">
		<div class="content">
			<div class="copyright">
				x © 2011
			</div>
			<div class="links">
				<a href="https://www.facebook.com/platform">About</a><a href="/policy/">Platform Policies</a><a href="https://www.facebook.com/policy.php">Privacy Policy</a>
			</div>
		</div>
	</div>
	<div id="fb-root">
	</div>
	<input type="hidden" autocomplete="off" id="post_form_id" name="post_form_id" value="d8f38124ed9e31ef3947198c6d26bff1"/>
	<div id="fb-root">
	</div>
	
</div>

</body>
</html>