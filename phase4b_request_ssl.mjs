import tokenModule from "./internal/token.js";
import userModel from "./models/user.js";

try {
	console.log("1. Checking and updating user email if needed...");
	const user = await userModel.query().findById(1);
	const targetEmail = "admin@lohnour.com";
	if (user.email !== targetEmail) {
		await userModel.query().findById(1).patch({ email: targetEmail });
		console.log(`User email updated from ${user.email} to ${targetEmail}`);
	} else {
		console.log("User email is already:", user.email);
	}

	console.log("2. Authenticating with NPM internal token...");
	const t = await tokenModule.getTokenFromEmail({
		identity: targetEmail,
		secret: "AdminLohnour2026!",
	});
	console.log("Got auth token.");

	console.log("3. Requesting Let's Encrypt certificate for pro.lohnour.com...");
	const certPayload = {
		provider: "letsencrypt",
		domain_names: ["pro.lohnour.com"],
		meta: {
			dns_challenge: false,
		},
	};

	const certRes = await fetch("http://127.0.0.1:3000/nginx/certificates", {
		method: "POST",
		headers: {
			Authorization: `Bearer ${t.token}`,
			"Content-Type": "application/json",
		},
		body: JSON.stringify(certPayload),
	});

	const certData = await certRes.json();
	console.log("Certificate creation HTTP status:", certRes.status);
	console.log("Certificate response:", JSON.stringify(certData, null, 2));

	if (!certRes.ok) {
		console.error("Certificate issuance failed!");
		process.exit(1);
	}

	const certId = certData.id;
	console.log(`Certificate issued successfully! ID: ${certId}`);

	console.log("4. Updating Proxy Host 1 to use SSL certificate and enable SSL...");
	const proxyUpdatePayload = {
		domain_names: ["pro.lohnour.com"],
		forward_scheme: "http",
		forward_host: "wordpress",
		forward_port: 80,
		certificate_id: certId,
		ssl_forced: true,
		hsts_enabled: false,
		hsts_subdomains: false,
		http2_support: true,
		block_exploits: true,
		allow_websocket_upgrade: true,
		caching_enabled: false,
		access_list_id: 0,
		advanced_config: "",
		meta: {},
		locations: [],
	};

	const proxyRes = await fetch("http://127.0.0.1:3000/nginx/proxy-hosts/1", {
		method: "PUT",
		headers: {
			Authorization: `Bearer ${t.token}`,
			"Content-Type": "application/json",
		},
		body: JSON.stringify(proxyUpdatePayload),
	});

	const proxyData = await proxyRes.json();
	console.log("Proxy host update HTTP status:", proxyRes.status);
	console.log("Proxy host response:", JSON.stringify(proxyData, null, 2));

	if (!proxyRes.ok) {
		console.error("Proxy host update failed!");
		process.exit(1);
	}

	console.log("SSL configuration completed successfully!");
	process.exit(0);
} catch (err) {
	console.error("FATAL ERROR:", err);
	process.exit(1);
}
