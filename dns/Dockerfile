FROM ubuntu:22.04

RUN apt-get update && \
    apt-get install -y bind9 bind9utils dnsutils vim && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Create necessary directories if they don't exist
RUN mkdir -p /etc/bind/zones
RUN mkdir -p /var/cache/bind
RUN mkdir -p /var/run/named && chown bind:bind /var/run/named

# Copy configuration files
COPY named.conf /etc/bind/named.conf
COPY named.conf.options /etc/bind/named.conf.options
COPY named.conf.local /etc/bind/named.conf.local
COPY zones/csn.local.db /etc/bind/zones/csn.local.db

# Validate configuration
RUN named-checkconf /etc/bind/named.conf || exit 1
RUN named-checkzone csn.local /etc/bind/zones/csn.local.db || exit 1

# Ensure correct permissions for BIND to write
RUN chown -R bind:bind /etc/bind
RUN chown -R bind:bind /var/cache/bind

EXPOSE 53/tcp
EXPOSE 53/udp

# Run named in foreground, group bind, config file, user bind
CMD ["/usr/sbin/named", "-g", "-c", "/etc/bind/named.conf", "-u", "bind"]