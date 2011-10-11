<div class="column three" id="sidebar">
	<% include SideMenu %>
</div>
<div class="column nine" id="content">
<% include ForumHeader %>
	<div id="TopicTree">
		<div id="Root">
			<% if ViewMode = Edit %>
				$ReplyForm(true)
			<% else %>
				$ReplyForm_Preview
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
		</div>
	</div>
	<% include ForumFooter %>
	<% include BreadCrumbs %>
</div>