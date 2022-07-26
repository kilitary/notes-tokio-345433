FROM amazonlinux:latest

# Arguments definition
ARG BASH_PROMPT
ARG MAXMIND_ACCOUNT_ID
ARG MAXMIND_LICENSE_KEY
ARG AWS_ACCESS_KEY_ID
ARG AWS_SECRET_ACCESS_KEY

# Control groups
VOLUME ["/sys/fs/cgroup"]

# Config bash prompt
RUN echo "" >> /root/.bashrc && \
  echo "alias pico=nano" >> /root/.bashrc && \
  echo "alias err='tail -f /var/www/vhosts/tokio/var/log/dev.log'" >> /root/.bashrc && \
  echo "export PS1='$BASH_PROMPT'" >> /root/.bashrc && \
  source /root/.bashrc

# Install packages
RUN amazon-linux-extras install -y php7.3 && \
  yum update -y && \
  yum install -y --setopt=tsflags=nodocs \
  php-xml \
  php-bcmath \
  php-opcache \
  php-mbstring \
  php-intl \
  php-gmp \
  php-shmop \
  php-zip \
  php-pear \
  php-devel \
  php-gd \
  mod_fcgid \
  libcurl-devel \
  zlib-devel \
  libpng-devel \
  nasm \
  git \
  ruby \
  awscli \
  procps \
  iputils \
  curl \
  wget \
  htop \
  lsof \
  nc \
  vi \
  vim \
  nano \
  strace \
  which \
  unzip \
  tar \
  openssl \
  bind-utils && \
  yum -y groupinstall --setopt=tsflags=nodocs 'Development Tools' && \
  yum -y clean all --setopt=tsflags=nodocs && \
  pecl install redis

# Install nginx from ALE
RUN amazon-linux-extras install nginx1.12 -y

# Install nodejs, npm and webpack
# why?
#RUN curl -sL https://rpm.nodesource.com/setup_11.x | bash - && yum install -y nodejs && npm install --global webpack@3.*

# replace default nginx config
COPY app/files/nginx/nginx.conf /etc/nginx/nginx.conf

# Change php-fpm to tcp
RUN sed -i 's/listen = \/run\/php-fpm\/www.sock/listen = 127.0.0.1:9000/g' /etc/php-fpm.d/www.conf

# Install composer
WORKDIR /tmp
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer


# Enable services on boot
RUN systemctl enable php-fpm.service && \
    systemctl enable nginx.service

# Config app path
RUN mkdir -p /var/www/vhosts/tokio
WORKDIR /var/www/vhosts/tokio

# Expose ports
EXPOSE 80 8080

# Config entrypoint
COPY app/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
CMD ["bash","-c","/entrypoint.sh"]

# Access app dir
WORKDIR /var/www/vhosts/tokio

#CMD /usr/sbin/init
