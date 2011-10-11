<div class="column three" id="sidebar">
	<% include SideMenu %>
</div>
<div class="column nine" id="content">
<% include ForumHeader %>
	<table class="forum-table">
		<thead>
		<tr class="table-header rowOne">
			<th class="page-numbers">
				<span><strong><% _t('PAGE','Page:') %></strong></span>
				<% control Posts.Pages %>
					<% if CurrentBool %>
						<span><strong>$PageNum</strong></span>
					<% else %>
						<a href="$Link">$PageNum</a>
					<% end_if %>
					<% if Last %><% else %>,<% end_if %>
				<% end_control %>
			</th>
			<th>
				<a class="icon arrow-down-skip" href="#footer" title="<% _t('CLICKGOTOEND','Click here to go the end of this post') %>"><% _t('GOTOEND','Go to End') %></a>
			</th>
			<th class="last">
				<% if CheckForumPermissions(post) %>
					<a class="icon reply" href="$ReplyLink" title="<% _t('CLICKREPLY','Click here to reply to this topic') %>"><% _t('REPLY','Reply') %></a>
				<% end_if %>
				<% if CurrentMember %>
					<a href="{$Link}unsubscribe/{$Post.TopicID}" class="icon unsubscribe <% if SubscribeLink %><% else %>hidden<% end_if %>" title="<% _t('CLICKUNSUBSCRIBE','Click here to Unsubscribe to this topic') %>"><% _t('UNSUBSCRIBE','Unsubscribe') %></a>
					<a href="{$Link}subscribe/{$Post.TopicID}" class="icon subscribe <% if SubscribeLink %>hidden<% end_if %>" title="<% _t('CLICKSUBSCRIBE','Click here to subscribe to this topic') %>"><% _t('SUBSCRIBE','Subscribe') %></a>
				<% end_if %>
			</th>
		</tr>
		
			<tr class="category-header rowTwo">
				<th class="author">
					<span><% _t('AUTHOR','Author') %></span>
				</th>
				<th class="topicTitle">
					<span><strong><% _t('TOPIC','Topic:') %></strong> $Post.Title</span>
				</th>
				<th class="reads last"<% if FlatThreadedDropdown %> rowspan="2"<% end_if %>>
					<span><strong>$Post.NumViews <% _t('VIEWS','Views') %></strong></span>
				</th>
			</tr>
		</thead>
	</table>
	<div id="posts">
		<% control Posts %>
			<div id="post{$ID}" class="$EvenOdd post-item">
				<% include SinglePost %>
			</div>
		<% end_control %>
	</div>
	<table class="forum-table">
		<tr class="category-footer rowTwo">
			<td class="author">&nbsp;</td>
			<td class="topicTitle">&nbsp;</td>
			<td class="reads last">
				<span><strong>$Post.NumViews <% _t('VIEWS','Views') %></strong></span>
			</td>
		</tr>
		<tr class="table-footer rowOne">
			<td class="page-numbers">
				<% if Posts.MoreThanOnePage %>
					<% if Posts.NotFirstPage %>
						<a class="prev" href="$Posts.PrevLink" title="<% _t('PREVTITLE','View the previous page') %>"><% _t('PREVLINK','Prev') %></a>
					<% end_if %>
				<% end_if %>
			</td>
			<td>
				<a class="icon arrow-up-skip" href="#header" title="<% _t('CLICKGOTOTOP','Click here to go the top of this post') %>"><% _t('GOTOTOP','Go to Top') %></a>
			</td>
			<td class="last">
				<% if CheckForumPermissions(post) %>
					<a class="icon reply" href="$ReplyLink" title="<% _t('CLICKREPLY', 'Click to Reply') %>"><% _t('REPLY', 'Reply') %></a>
				<% end_if %>
				<% if Posts.MoreThanOnePage %>
					<% if Posts.NotLastPage %>
						<a class="next" href="$Posts.NextLink" title="<% _t('NEXTTITLE','View the next page') %>"><% _t('NEXTLINK','Next') %> &gt;</a>
					<% end_if %>
				<% end_if %>
			</td>
		</tr>
	</table>
	
	<% if isAdmin %>
		<div class="clear"><!-- --></div>
		<div id="forum-admin-features">
			<h3>Forum Admin Features</h3>
			$AdminFormFeatures
		</div>
	<% end_if %>
<% include ForumFooter %>
</div>