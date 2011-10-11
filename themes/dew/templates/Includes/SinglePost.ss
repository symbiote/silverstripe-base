<div class="user-information">
	<% control Author %>
	<img class="userAvatar" src="$FormattedAvatar" alt="Avatar" /><br />
	<a class="authorTitle" href="$Link" title="<% _t('GOTOPROFILE','Go to this User\'s Profile') %>">$Nickname</a><br />
	<% if ForumRank %><span class="rankingTitle expert">$ForumRank</span><br /><% end_if %>
	<% if NumPosts %><span class="postCount">$NumPosts posts</span><% end_if %>
	<% end_control %>
</div>
<div class="poster-content">
	<h3><a href="$Link">$Title</a></h3>
	<p class="date">$Created.Long at $Created.Time 
		<% if Updated %>
			| <strong><% _t('LASTEDITED','Last edited:') %> $Updated.Long <% _t('AT') %> $Updated.Time</strong>
		<% end_if %>
	</p>
	<div class="postType">
		$Content.Parse(BBCodeParser)
	</div>
	<% if EditLink || DeleteLink %>
		<div class="post-modifiers">
			<% if EditLink %>
				<span class="icon edit">$EditLink</span>
			<% end_if %>
			<% if DeleteLink %>
				<span class="icon delete">$DeleteLink</span>
			<% end_if %>
			<% if MarkAsSpamLink %>
				$MarkAsSpamLink
			<% end_if %>
		</div>
	<% end_if %>	
	<% if DisplaySignatures %>
		<% control Author %>
			<% if Signature %>
				<div class="signature">
					<p>$Signature</p>
				</div>
			<% end_if %>
		<% end_control %>
	<% end_if %>

	<% if Attachments %>
		<div class="attachments">
			<strong><% _t('ATTACHED','Attached Files') %></strong> 
			<ul class="attachmentList">
			<% control Attachments %>
				<li class="attachment">
					<a href="$Link"><img src="$Icon"></a>
					<a href="$Link">$Name</a><br />
					<% if ClassName = "Image" %>$Width x $Height - <% end_if %>$Size
				</li>
			<% end_control %>
			</ul>
		</div>
	<% end_if %>
</div>
