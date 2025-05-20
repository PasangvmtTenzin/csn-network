# Enterprise Network Simulation with Docker

This project sets up a simulated enterprise network using Docker and Docker Compose.
It includes:
1.  A DNS Server (BIND9)
2.  A Web Server (Apache + PHP)
3.  A Database Server (MariaDB)
4.  A Client Test Machine (Alpine Linux with network tools)

## Project Structure

```plaintext
    csn-network/
├── docker-compose.yml
├── client/ # Client machine files
│ └── Dockerfile
├── dns/
│ ├── Dockerfile
│ ├── named.conf
│ ├── named.conf.options
│ ├── named.conf.local
│ └── zones/
│ └── csn.local.db
├── web/
│ ├── Dockerfile
│ └── app/
│ ├── index.php
│ ├── style.css
│ └── db_connect.php
├── db_init/
│ └── init.sql
└── README.md

```

## Services & Configuration

<!-- ... (DNS, Web, DB sections remain mostly the same) ... -->

4.  **Client Machine (`client_machine`)**
    *   **FQDN:** `client.csn.local` (resolvable internally)
    *   **IP Address:** `10.10.10.8/24`
    *   **OS:** Alpine Linux (minimal)
    *   **Tools:** `dig`, `nslookup`, `curl`, `ping`
    *   **Description:** Used for testing network connectivity, DNS resolution, and web access within the Docker network.

## Prerequisites

*   Docker installed and running.
*   Docker Compose installed.

## How to Run

1.  **Clone/Download:** Get all files into a directory (e.g., `enterprise_network_project`).
2.  **Navigate:** Open a terminal and `cd` into the `enterprise_network_project` directory.
3.  **Build and Start:**
    ```bash
    docker-compose up --build -d
    ```
    The `--build` flag ensures images are rebuilt if Dockerfiles change. `-d` runs in detached mode.
4.  **Check Status:**
    ```bash
    docker-compose ps
    # To see logs:
    # docker-compose logs dns_server
    # docker-compose logs web_server
    # docker-compose logs db_server
    # docker-compose logs client_machine
    ```

## Testing

### 1. Testing from the Client Container (`client.csn.local` - 10.10.10.8)

This is the primary method for testing internal network functionality.

1.  **Access the client container's shell:**
    ```bash
    docker-compose exec client_machine sh
    ```
    You are now inside the `client_machine` container.

2.  **Verify IP and DNS Configuration (inside client container):**
    *   Check IP:
        ```sh
        ip addr show eth0 # Or the relevant network interface
        # Expected output should include: inet 10.10.10.8/24
        ```
    *   Check DNS resolver configuration:
        ```sh
        cat /etc/resolv.conf
        # Expected output: nameserver 10.10.10.5
        ```

3.  **Test DNS Resolution (inside client container):**
    *   Using `dig`:
        ```sh
        dig web.csn.local
        # Expected: Answer section shows web.csn.local. A 10.10.10.6

        dig db.csn.local
        # Expected: Answer section shows db.csn.local. A 10.10.10.7

        dig dns.csn.local
        # Expected: Answer section shows dns.csn.local. A 10.10.10.5

        dig client.csn.local
        # Expected: Answer section shows client.csn.local. A 10.10.10.8

        dig google.com
        # Expected: Resolves to Google's public IPs (tests forwarding)
        ```
    *   Using `nslookup` (alternative):
        ```sh
        nslookup web.csn.local
        nslookup db.csn.local
        nslookup google.com
        ```

4.  **Test Web Server Access (inside client container):**
    *   Using `curl` to fetch the webpage:
        ```sh
        curl http://web.csn.local
        # Expected: HTML output of the Notes application.

        curl -I http://web.csn.local
        # Expected: HTTP/1.1 200 OK and other headers.
        ```
    *   Test with direct IP:
        ```sh
        curl http://10.10.10.6
        ```

5.  **Test Network Connectivity (inside client container):**
    *   Using `ping`:
        ```sh
        ping -c 3 dns.csn.local  # Pings 10.10.10.5
        ping -c 3 web.csn.local  # Pings 10.10.10.6
        ping -c 3 db.csn.local   # Pings 10.10.10.7
        ```

6.  **Exit the client container's shell:**
    ```sh
    exit
    ```

### 2. Optional: Testing with an External Windows Client

If required by the assignment for demonstration, you can still configure an external Windows machine (or a VM).
*   **IP Address:** Ensure the external client uses an IP **different** from `10.10.10.8` if the `client_machine` container is running (e.g., `10.10.10.9`). If you must use `10.10.10.8` for the external Windows client, stop the Docker client first: `docker-compose stop client_machine`.
*   **Configuration:**
    *   IP: `10.10.10.9` (or `10.10.10.8` if Docker client is stopped)
    *   Subnet: `255.255.255.0`
    *   Gateway: `10.10.10.1`
    *   Preferred DNS: `10.10.10.5`
*   **Testing:** Use `nslookup` in Command Prompt and a web browser to access `http://web.csn.local` as previously described.

**Recommendation for Demonstration:**
Primarily use the `client_machine` container to demonstrate the full internal network setup. The external Windows client can be shown as a supplementary test.

## Stopping and Cleaning Up
<!-- ... (Stopping and Cleaning Up section remains the same) ... -->