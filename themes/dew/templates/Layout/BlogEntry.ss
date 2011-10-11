<div class="column three">
	<% include BlogSideBar %>
</div>
<div class="column nine" id="content">
	<div class="blog-item">
		<% if IsWYSIWYGEnabled %>
			$Content
		<% else %>
			$ParsedContent
		<% end_if %>
		<p>
			<% if TagsCollection %>
				<span class="icon tags"><% _t('TAGS', 'Tags:') %> 
				<% control TagsCollection %>
					<a class="tag" href="$Link" title="<% _t('VIEWALLPOSTTAGGED', 'View all posts tagged') %> '$Tag'" rel="tag">$Tag</a>
				<% end_control %>
				</span>
			<% end_if %>
			<span class="icon comments">$Comments.Count <% _t('COMMENTS', 'Comments') %></span>
			<% if Author %>
				<% _t('POSTEDBY', 'Posted by') %> $Author.XML <% _t('POSTEDON', 'on') %>
			<% end_if %>
			$Date.Long
		</p>
		<% if IsOwner %><p><a class="button" href="$EditURL" id="editpost" title="<% _t('EDITTHIS', 'Edit this post') %>"><% _t('EDITTHIS', 'Edit this post') %></a> <a class="button" href="$Link(unpublishPost)" id="unpublishpost"><% _t('UNPUBLISHTHIS', 'Unpublish this post') %></a></p><% end_if %>
	</div>
	
	<% if TrackBacksEnabled %>
		<% include TrackBacks %>
	<% end_if %>
	$PageComments
	<% include BreadCrumbs %>
</div>