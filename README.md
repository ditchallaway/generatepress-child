# Brokertricks GeneratePress Child Theme

## Deployment Architecture

**Important Context for Development:** 
All code modifications should be made to the local clones in this repository. 

This environment does **not** push directly to the live server via FTP or SSH file copies.

### Deployment Flow:
1. **Local Modifications:** Make and test your PHP, CSS, and JS changes locally.
2. **Commit and Push:** Commit your changes to the local Git repository and push them to GitHub (`main` branch).
3. **Webhook Trigger:** Pushing to GitHub fires a POST payload with an HMAC SHA-256 signature (`X-Hub-Signature-256`) to the VPS webhook endpoint (`http://<VPS-IP>:3001/webhook`).
4. **Server Listener:** A Node.js Express service (`webhook.js`) managed by PM2 listens on port `3001`, verifies the GitHub secret signature, and executes `git pull origin main` to pull the latest changes directly into the live theme directory.

---

### Webhook Service Management (VPS)

* **Script Location:** Located outside the web root (e.g., `~/webhook.js`).
* **Port / Firewall:** Operates on port `3001` (requires UFW rule `sudo ufw allow 3001/tcp`).
* **Process Manager:** Managed via PM2 under the process name `github-webhook`.

**Useful Server Commands:**
```bash
# View live webhook logs
pm2 logs github-webhook

# Check process status
pm2 status

# Restart the listener
pm2 restart github-webhook
