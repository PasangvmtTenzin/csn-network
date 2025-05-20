FROM alpine:latest

# Install common networking tools:
# bind-tools: for dig and nslookup
# curl: for web requests
# iputils: for ping
# net-tools: for ifconfig (optional, 'ip addr' is preferred)
# nano: simple text editor (optional)
RUN apk update && \
    apk add --no-cache bind-tools curl iputils net-tools nano

# Keep the container running so we can exec into it
CMD ["tail", "-f", "/dev/null"]