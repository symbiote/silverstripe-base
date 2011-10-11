<div class="column three" id="sidebar">
	<% include SideMenu %>
</div>
<div class="column nine" id="content">
	<% include ForumHeader %>
	$Content
	<% if Form %>
		<div id="user-profile">
			$Form
		</div>
	<% end_if %>
	<% include ForumFooter %>
	<% include BreadCrumbs %>
</div>