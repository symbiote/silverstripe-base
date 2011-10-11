<div id="forum-footer">
	<div style="overflow:hidden;">
		<form action="<% if ForumHolderLink %>{$ForumHolderLink}<% else %>{$Link}<% end_if %>search/" id="forum-search" method="get">
			<fieldset>
				<!-- <label><% _t('SEARCH','Search:') %></label> -->
				<input class="text" type="text" name="Search" />
				<input class="action submit" type="submit" value="<% _t('SEARCHBUTTON','Search') %>"/>
			</fieldset>
		</form>
		<form id="forum-jump">
			<label class="jumpTo"><% _t('JUMPTO','Jump to:') %></label>
			<select onchange="if(this.value) location.href = this.value">
				<option value=""><% _t('SELECT','Select') %></option>
				<% if ShowInCategories %>
					<% control Forums %>
						<optgroup label="$Title">
							<% control Forums %>
								<% if CheckForumPermissions %>
									<option value="$Link">$Title</option>
								<% end_if %>
							<% end_control %>
						</optgroup>
					<% end_control %>
					
				<% else %>
					<% control Forums %>
						<% if CheckForumPermissions %>
							<option value="$Link">$Title</option>
						<% end_if %>
					<% end_control %>
				<% end_if %>
			</select>
		</form>

	</div>
	<p>
		<strong><% _t('CURRENTLYON','Currently Online:') %></strong>
		<% if CurrentlyOnline %>
			<% control CurrentlyOnline %>
				<% if Link %><a href="$Link" title="<% if Nickname %>$Nickname<% else %>Anon<% end_if %><% _t('ISONLINE',' is online') %>"><% if Nickname %>$Nickname<% else %>Anon<% end_if %></a><% else %><span>Anon</span><% end_if %><% if Last %><% else %>,<% end_if %>
			<% end_control %>
		<% else %>
				<span><% _t('NOONLINE','There is nobody online.') %></span>
		<% end_if %>
	
	
	<% if Moderators %><br/><strong>Moderators:</strong> <% control Moderators %><a href="$Link">$Nickname</a><% if Last %><% else %>, <% end_if %><% end_control %><% end_if %>
	

	<br/><strong><% _t('LATESTMEMBER','Welcome to our latest member:') %></strong>			
	<% control LatestMember %>
		<% if Link %><a href="$Link" title="<% if Nickname %>$Nickname<% else %>Anon<% end_if %> <% _t('ISONLINE') %>"><% if Nickname %>$Nickname<% else %>Anon<% end_if %></a><% else %><span>Anon</span><% end_if %><% if Last %><% else %>,<% end_if %> 
	<% end_control %>
	
		<%-- stats --%>
		<br/>$TotalPosts <strong><% _t('POSTS','Posts') %></strong> <% _t('IN','in') %> $TotalTopics <strong><% _t('TOPICS','Topics') %></strong> <% _t('BY','by') %> $TotalAuthors <strong><% _t('MEMBERS','members') %></strong>
	</p>
</div>