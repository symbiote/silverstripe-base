<% if Menu(1) %>
	<ul id="footer-menu">
		<% control Menu(1) %>
		<li class="$LinkingMode">
			<a href="$Link">$MenuTitle.XML</a>

				<% if Children %>
					<ul>
					<% control Children %>
						<li class="$LinkingMode">
							<a href="$Link">$MenuTitle.XML</a>
						</li>
					<% end_control %>
					</ul>
				<% end_if %>

		</li>
		<% end_control %>
	</ul>
<% end_if %>