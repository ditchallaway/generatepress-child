# Brokertricks GeneratePress Child Theme

This child theme powers the custom WordPress platform for **Brokertricks**, a digitally enabled service delivering automated and visual real estate assets. 

Developers building or extending features within this theme should account for the active environment dependencies and automatic deployment workflow detailed below.

---

## Active Environment & Dependencies

When adding features, custom templates, or function hooks to this theme, keep in mind that the following core plugins are installed and active on the site:

* **SureCart:** Handles commerce functionality, order processing, custom customer roles, and digital service checkout flows.
* **Secure Custom Fields (SCF):** Manages structured custom fields, metadata extensions, and content models across post types.

Ensure any new PHP snippets, custom block templates, or REST API extensions interact safely with these plugins when applicable.

---

## Deployment Architecture

**Important Context for Development:** 
All code modifications should be made to the local clones in this repository. 

This environment automatically deploys changes to the production server via an automated Git webhook pipeline upon pushing to the `main` branch.

### Deployment Flow:
1. **Local Modifications:** Make and test your PHP, CSS, and JS changes locally.
2. **Commit and Push:** Commit your changes to the local Git repository and push them to GitHub (`main` branch).
3. **Webhook Trigger:** Pushing to GitHub fires an authenticated POST payload with an HMAC SHA-256 signature (`X-Hub-Signature-256`) to the VPS webhook endpoint (`http://104.168.102.178:3001/webhook`).
4. **Server Pull:** A Node.js Express service (`webhook.js`) managed by PM2 listens on port `3001`, verifies the GitHub secret signature, and automatically executes `git pull origin main` to pull the latest changes directly into the live theme directory (`/home/brokertricks/htdocs/brokertricks.com/wp-content/themes/generatepress-child`).

---

## Webhook Service Management (VPS)

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
```
# SureCart Order Notes — Data Shape for the Fulfillment Dashboard

A completed order has a SureCart **order note** whose `metadata` is the fulfillment
record:

| key | type | meaning |
|---|---|---|
| `pack` | string | product/package identifier, e.g. `"single_map"` |
| `parcel_number` | string | assessor's parcel number (APN), e.g. `"RPS0562002007TA"` |
| `fulfilled_at` | string | ISO-8601 timestamp delivery completed |
| `kml_url` | string | short link to the parcel boundary KML (when present) |
| `overhead_url` | string | short link to the overhead/hero image — **always the first image key** |
| `<shot>_url` | string | one short link per oblique shot, keyed by shot name (`north_url`, `east_url`, …) |
| `shot_N_url` | string | fallback key when a shot has no name (`shot_2_url`, `shot_3_url`, …) |

- All URLs are short links (`link.brokertricks.com/...`).
- The first image is always `overhead_url`; subsequent images use named/fallback keys.

### Example (`single_map`)

```json
{
  "pack": "single_map",
  "parcel_number": "RPS0562002007TA",
  "fulfilled_at": "2026-09-27T06:44:23.800Z",
  "kml_url": "https://link.brokertricks.com/2rvjap",
  "overhead_url": "https://link.brokertricks.com/yahaaj"
}
```

### Per-product image keys

| `pack` | image keys |
|---|---|
| `single_map` | `overhead_url` |
| full 5-shot pack | `overhead_url` + `north_url`, `east_url`, `south_url`, `west_url` |
### Per-product image keys

| `pack` | image keys |
|---|---|
| `single_map` | `overhead_url` |
| full 5-shot pack | `overhead_url` + `north_url`, `east_url`, `south_url`, `west_url` |
