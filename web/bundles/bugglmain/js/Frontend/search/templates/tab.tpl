<section class="multi">
	<ul>
		<li <% if (selected=="relevant") { %> <%= 'class="selected"' %><% } %>><a class="link" href="#" data="relevant">Relevant</a></li>
		<li <% if (selected=="recent") { %> <%= 'class="selected"' %> <% } %>><a class="link" href="#"  data="recent">Latest </a></li>
		<li <% if (selected=="download") { %> <%= 'class="selected"' %> <% } %>><a class="link" href="#" data="download">Most Downloaded</a></li>
	</ul>
</section>