FROM debian:stretch

ENV DEBIAN_FRONTEND noninteractive
ENV container docker
ENV init /lib/systemd/systemd
ENV LC_ALL C

RUN apt-get update && apt-get -y install procps less systemd ; \
    [ -e /lib/systemd/system/sysinit.target.wants/ ] && \
      find /lib/systemd/system/sysinit.target.wants/ ! -name 'systemd-tmpfiles-setup.service' -type f -o -type l -exec rm -f {} \; || true; \
    [ -e /lib/systemd/system/local-fs.target.wants/ ] && \
      find /lib/systemd/system/multi-user.target.wants/ -type f -o -type l -exec rm -f {} \; || true; \
    find /etc/systemd/system/*.wants/ -type f -o -type l -exec rm -f {} \; || true; \
    [ -e /lib/systemd/system/local-fs.target.wants/ ] && \
      find /lib/systemd/system/local-fs.target.wants/ -type f -o -type l -exec rm -f {} \; || true; \
    [ -e /lib/systemd/system/sockets.target.wants/ ] && \
      find /lib/systemd/system/sockets.target.wants/ -iname '*udev*' -o -iname '*initctl*' -exec rm -f {} \; || true; \
    [ -e /usr/lib/tmpfiles.d/systemd-nologin.conf ] && rm -f /usr/lib/tmpfiles.d/systemd-nologin.conf || true

RUN sed -i'' -e 's/.*SystemMaxUse=.*/SystemMaxUse=500M/' /etc/systemd/journald.conf ; \
    mkdir -p /usr/local/lib/systemd/system

ADD docker/systemd/minera.service /usr/local/lib/systemd/system/

RUN ln -s /usr/local/lib/systemd/system/minera.service /etc/systemd/system/multi-user.target.wants/minera.service

RUN printf '# Do not install recommended and suggested packages by default\n\
APT::Install-Recommends "0";\n\
APT::Install-Suggests "0";\n' > /etc/apt/apt.conf.d/docker-skip-recommends-suggests

RUN apt-get update && apt-get install -y lighttpd php5-cgi
RUN lighty-enable-mod fastcgi
RUN lighty-enable-mod fastcgi-php

RUN mkdir -p /etc/systemd/system/lighttpd.service.d/
ADD docker/systemd/lighttpd.conf /etc/systemd/system/lighttpd.service.d/lighttpd.conf
ADD docker/lighttpd/conf-available/15-fastcgi-php.conf /etc/lighttpd/conf-available/15-fastcgi-php.conf

RUN apt-get install -y curl screen php5-cli php5-curl
RUN echo "postfix postfix/main_mailer_type string 'Internet site'" >> preseed.txt; \
    echo "postfix postfix/mailname string mail.example.com" >> preseed.txt; \
    debconf-set-selections preseed.txt && rm preseed.txt

ADD . /var/www/minera
RUN apt-get -y install build-essential pkg-config file libbase58-dev libjansson-dev && cd /var/www/minera/minera-bin/src/libblkmaker/ && ./configure && make || true; \
    cd /var/www/minera && sed -e 's/^[[:space:]]*sudo//' -e '/nvm/d; /NVM/d' ./install_minera.sh | /bin/bash && \
    apt-get -y purge build-essential redis-server && \
    dpkg-query  --show --showformat='${binary:Package}\n' | grep '\-dev$' | xargs apt-get purge -y && \
    apt-get -y autoremove --purge && \
    apt-get -y install npm nodejs-legacy nodejs redis-tools && apt-mark manual npm nodejs-legacy nodejs redis-tools && \
    apt-get -y clean && \
    rm -rf /var/lib/apt/lists/*

RUN [ -e /etc/systemd/system/default.target ] && rm /etc/systemd/system/default.target || true ; \
    ln -s /usr/lib/systemd/system/multi-user.target /etc/systemd/system/default.target

#RUN systemctl set-default multi-user.target


VOLUME [ "/sys/fs/cgroup" ]
# Workaround hack for missing /sys/fs/cgroup (This works on Docker Machine, but YMMV)
CMD ["/bin/bash", "-c", "mount -oremount,rw /sys/fs/cgroup; mkdir /sys/fs/cgroup/systemd; mount -oremount,ro /sys/fs/cgroup; exec /lib/systemd/systemd"]

# TODO: If possible, somehow cleanup above hack, & replace with either:
#ENTRYPOINT ["/lib/systemd/systemd"]
#CMD ["/usr/sbin/init"]
