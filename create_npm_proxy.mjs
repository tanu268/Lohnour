import tokenModule from "./internal/token.js";

try {
	console.log("Authenticating...");
	const t = await tokenModule.getTokenFromEmail({
		identity: "admin@lohnour.local",
		secret: "AdminLohnour2026!",
	});
	console.log("Got token:", t.token.substring(0, 15) + "...");

	const payload = {
		domain_names: ["pro.lohnour.com"],
		forward_scheme: "http",
		forward_host: "wordpress",
		forward_port: 80,
		block_exploits: true,
		allow_websocket_upgrade: true,
		caching_enabled: false,
		access_list_id: 0,
		certificate_id: 0,
		ssl_forced: false,
		advanced_config: "",
		meta: {},
		locations: [],
	};

	console.log("Calling API to create proxy host...");
	const res = await fetch("http://127.0.0.1:3000/nginx/proxy-hosts", {
		method: "POST",
		headers: {
			Authorization: `Bearer ${t.token}`,
			"Content-Type": "application/json",
		},
		body: JSON.stringify(payload),
	});

	const data = await res.json();
	console.log("HTTP STATUS:", res.status);
	console.log("RESPONSE:", JSON.stringify(data, null, 2));

	if (!res.ok) {
		process.exit(1);
	}
	process.exit(0);
} catch (err) {
	console.error("ERROR:", err);
	process.exit(1);
}
