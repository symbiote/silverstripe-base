<div class="blog-item">
	<h2><a href="$Link" title="<% _t('VIEWFULL', 'View full post titled -') %> '$Title'">$MenuTitle</a></h2>
	<p>$ParagraphSummary</p>
	<p><a href="$Link" class="button" title="Read the full post">Read</a></p>
	<% if TagsCollection %>
		<p>
			<span class="icon tags"><% _t('TAGS', 'Tags:') %> 
			<% control TagsCollection %>
				<a class="tag" href="$Link" title="View all posts tagged '$Tag'" rel="tag">$Tag</a>
			<% end_control %>
			</span>
			<span class="icon comments">
				<a href="$Link#comments" title="View comments posted">$Comments.Count <% _t('COMMENTS', 'comments') %></a>
			</span>
			<% if Author %>
				<% _t('POSTEDBY', 'Posted by') %> $Author.XML <% _t('POSTEDON', 'on') %>
			<% end_if %>
			$Date.Long
		</p>
	<% end_if %>
</div>
