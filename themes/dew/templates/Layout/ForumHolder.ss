<div class="column three" id="sidebar">
	<% include SideMenu %>
</div>
<div class="column nine" id="content">
	<% include ForumHeader %>
		<div class="forum-body">
			<table class="forum-table">
				<% if GlobalAnnouncements %>
					<tr class="category"><td colspan="4"><% _t('ANNOUNCEMENTS', 'Announcements') %></td></tr>
					<% control GlobalAnnouncements %>
						<% include ForumHolder_List %>
					<% end_control %>
				<% end_if %>
				<% if ShowInCategories %>
					<% control Forums %>
						<tr class="category"><td colspan="4">$Title</td></tr>
						<thead>
							<tr class="category-header">
								<th class="odd"><% if Count = 1 %><% _t('FORUM','Forum') %><% else %><% _t('FORUMS', 'Forums') %><% end_if %></th>
								<th class="even" width="80"><% _t('THREADS','Threads') %></th>
								<th class="odd" width="80"><% _t('POSTS','Posts') %></th>
								<th class="even last" width="80"><% _t('LASTPOST','Last Post') %></th>
							</tr>
						</thead>
						<% control CategoryForums %>
							<% if CheckForumPermissions %>
								<% include ForumHolder_List %>
							<% end_if %>
						<% end_control %>
					<% end_control %>
				<% else %>
					<thead>
						<tr class="category-header">
							<th class="odd"><% _t('FORUM','Forum') %></th>
							<th class="even" width="80"><% _t('THREADS','Threads') %></th>
							<th class="odd" width="80"><% _t('POSTS','Posts') %></th>
							<th class="even last" width="80"><% _t('LASTPOST','Last Post') %></th>
						</tr>
					</thead>
					<% control Forums %>
						<% if CheckForumPermissions %>
							<% include ForumHolder_List %>
						<% end_if %>
					<% end_control %>
				<% end_if %>
			</table>
		</div>
	<% include ForumFooter %>
</div>