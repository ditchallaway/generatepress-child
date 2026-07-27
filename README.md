# Brokertricks GeneratePress Child Theme

## Deployment Architecture

**Important Context for Development:** 
All code modifications should be made to the local clones in this repository. 

This environment does **not** push directly to the live server via FTP or SSH file copies.

### Deployment Flow:
1. **Local Modifications:** Make and test your PHP, CSS, and JS changes locally.
2. **Commit and Push:** Commit your changes to the local Git repository and push them to GitHub.
3. **Webhook Trigger:** Pushing to GitHub fires a webhook to the live VPS.
4. **Server Pull:** The VPS automatically executes a `git pull` to fetch the latest changes into the live environment.

*(Note: The `n8n-as-code` repository handles its state and deployments differently, typically via the `n8nac` CLI syncing tool rather than simple git webhook pulls).*
