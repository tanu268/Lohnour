# ==============================================================================
# LOHNOUR PRO - CANONICAL WORDPRESS 6.7.11 CONTAINER IMAGE SPECIFICATION
# Target Architecture: Oracle Ampere A1 (ARM64 / aarch64)
# Base Runtime: Official Docker WordPress 6.7.2 Apache image (PHP 8.2 / Debian Bookworm)
# Upgrade: Official WordPress 6.7.11 maintenance and security release
# ==============================================================================

FROM wordpress:6.7.2-apache

# Metadata and cryptographic verification anchors
ARG WP_VERSION=6.7.11
ARG WP_SHA256=af8d8b3c03cedbcbe5dd415cf316fb2455faadc2692ff828a54d828f1c8448be

LABEL org.opencontainers.image.title="Lohnour Pro WordPress Application" \
      org.opencontainers.image.description="WordPress 6.7.11 on Apache and PHP 8.2 with preserved official Docker entrypoint" \
      org.opencontainers.image.version="6.7.11" \
      org.opencontainers.image.authors="Lohnour DevOps Team"

# Download, verify, and replace WordPress core in /usr/src/wordpress
# CRITICAL: Preserves /usr/src/wordpress/wp-config-docker.php required for WORDPRESS_DB_* variables
RUN set -eux; \
    # 1. Download official WordPress 6.7.11 distribution archive
    curl -fsSL -o /tmp/wordpress.tar.gz "https://downloads.wordpress.org/release/wordpress-${WP_VERSION}.tar.gz"; \
    \
    # 2. Verify archive integrity using published cryptographic hash
    echo "${WP_SHA256} */tmp/wordpress.tar.gz" | sha256sum -c -; \
    \
    # 3. Preserve critical official Docker entrypoint configuration template
    cp /usr/src/wordpress/wp-config-docker.php /tmp/wp-config-docker.php; \
    \
    # 4. Extract 6.7.11 distribution into temporary staging location
    mkdir -p /tmp/wordpress-extracted; \
    tar -xzf /tmp/wordpress.tar.gz -C /tmp/wordpress-extracted; \
    \
    # 5. Atomically replace core skeleton in /usr/src/wordpress
    rm -rf /usr/src/wordpress; \
    mv /tmp/wordpress-extracted/wordpress /usr/src/wordpress; \
    \
    # 6. Restore the official Docker wp-config-docker.php configuration template
    cp /tmp/wp-config-docker.php /usr/src/wordpress/wp-config-docker.php; \
    \
    # 7. Clean up temporary build artifacts to minimize layer size
    rm -rf /tmp/wordpress.tar.gz /tmp/wordpress-extracted /tmp/wp-config-docker.php; \
    \
    # 8. Set canonical ownership and permissions for Apache execution
    chown -R www-data:www-data /usr/src/wordpress

# Inherits standard official entrypoint:
# ENTRYPOINT ["docker-entrypoint.sh"]
# CMD ["apache2-foreground"]

