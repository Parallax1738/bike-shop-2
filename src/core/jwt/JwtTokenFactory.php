<?php
	
	class JwtTokenFactory
	{
		public function createTokenFromPayload(JwtPayload $payload): JwtToken {
			return new JwtToken([], $payload);
		}
		
		public function decipherToken(string $token) {
		
		}
	}