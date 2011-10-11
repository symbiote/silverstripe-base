<!doctype html>  

<html lang="en">
	<head>
		<% base_tag %>
		
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0"/>
		
		$MetaTags
	
		<% require themedCSS(layout) %>
		<% require themedCSS(typography) %>
		<% require themedCSS(form) %>
	</head>

	<body>
		<div id="header">
			<div class="container">
				<a id="logo" href="home/">$SiteConfig.Title</a>
				<ul id="navigation">
					<% control Menu(1) %>
						<li class="$LinkingMode"><a href="$Link">$MenuTitle.XML</a></li>
					<% end_control %>
				</ul>
			</div>
		</div>
		
		<div id="banner">
			<div class="container">
				<h1>$Title</h1>
			</div>
		</div>
		
		
		<div id="layout">
			<div class="container columnset">
				$Layout
			</div>
		</div>
		
		<div id="footer">
			<div class="container columnset">
				<div class="column twelve">
					<p><% _t('COPYRIGHT', 'Copyright') %> &copy; $Now.Year | <% _t('POWEREDBY', 'Powered by') %> <a href="http://silverstripe.org">SilverStripe Open Source CMS</a></p>
					<% include FooterMenu %>
				</div>
			</div>
		</div>
	</body>
</html>
