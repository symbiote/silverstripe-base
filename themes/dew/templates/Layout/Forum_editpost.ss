<div class="column three" id="sidebar">
	<% include SideMenu %>
</div>
<div class="column nine" id="content">
	<% include ForumHeader %>
		<% if CurrentMember %>
			<% if ViewMode = Edit %>
				$EditForm
			<% else %>
				$EditForm_Preview
			<% end_if %>
		<% else %>
			<p class="error message"><% _t('NOTLOGGEDINPOST','If you would like to post, please <a href="Security/login" title="log in">log in</a> or <a href="ForumMemberProfile/register" title="register">register</a> first.') %></p>
		<% end_if %>
		<% if BBTags %>
			<div id="BBTagsHolder" class="hide">
				<h4><% _t('AVAILABLEBB','Available BB Code tags') %></h4>
				<ul>
					<% control BBTags %>
						<li class="$FirstLast">
							<strong>$Title</strong><% if Description %>: $Description<% end_if %> <span class="example">$Example</span>
						</li>
					<% end_control %>
				</ul>
			</div>
		<% end_if %>
	<% include ForumFooter %>
	<% include BreadCrumbs %>
</div>