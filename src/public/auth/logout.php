<h1>Logging Out...</h1>
<script>
	cookieStore
		.get('token')
		.then(processCookie);
    
    function processCookie(cookie) {
        // If a cookie exists, delete it
    	if (cookie) {
            cookieStore
				.delete('token')
				.then(rerouteToHome)
		}
	}
    
    function rerouteToHome() {
    	console.log("Deleted Cookie. Rerouting");
        document.location.href = '/';
	}
</script>
