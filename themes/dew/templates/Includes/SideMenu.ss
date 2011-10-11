<% if Menu(2) %>
	<ul id="menu">
		<% control Menu(2) %>
			<li class="$LinkingMode">
				<a href="$Link">$MenuTitle.XML</a>
				<% if LinkOrSection = section %>
					<% if Children %>
						<ul class="menu-3">
							<% control Children %>
								<li class="$LinkingMode">
									<a href="$Link">$MenuTitle.XML</a>
									
									<% if LinkOrSection = section %>
										<% if Children %>
											<ul class="menu-4">
												<% control Children %>
													<li class="$LinkingMode">
														<a href="$Link">$MenuTitle.XML</a>
													</li>
												<% end_control %>
											</ul>
										<% end_if %>
									<% end_if %>
								</li>
							<% end_control %>
						</ul>
					<% end_if %>
				<% end_if %>
			</li>
		<% end_control %>
	</ul>
<% end_if %>
