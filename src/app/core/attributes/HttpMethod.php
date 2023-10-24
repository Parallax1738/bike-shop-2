<?php
	
	namespace bikeshop\app\core\attributes;
	
	enum HttpMethod
	{
		case GET;
		case POST;
		case DELETE;
		case PUT;
		case PATCH;
	}