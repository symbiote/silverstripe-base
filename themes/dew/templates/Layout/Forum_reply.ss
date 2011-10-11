<div class="column three" id="sidebar">
	<% include SideMenu %>
</div>
<div class="column nine" id="content">
	<% include ForumHeader %>
		$ReplyForm
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
		
		<div id="posts">
			<% control Posts(DESC) %>
				<div class="$EvenOdd post-item">
					<% include SinglePost %>
				</div>
			<% end_control %>
		</div>
		
	<% include ForumFooter %>
	<% include BreadCrumbs %>
</div>
