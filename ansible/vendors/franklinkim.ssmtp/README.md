# Ansible SSMTP Role

[![Build Status](https://img.shields.io/travis/weareinteractive/ansible-ssmtp.svg)](https://travis-ci.org/weareinteractive/ansible-ssmtp)
[![Galaxy](http://img.shields.io/badge/galaxy-franklinkim.ssmtp-blue.svg)](https://galaxy.ansible.com/list#/roles/3916)
[![GitHub Tags](https://img.shields.io/github/tag/weareinteractive/ansible-ssmtp.svg)](https://github.com/weareinteractive/ansible-ssmtp)
[![GitHub Stars](https://img.shields.io/github/stars/weareinteractive/ansible-ssmtp.svg)](https://github.com/weareinteractive/ansible-ssmtp)

> `ssmtp` is an [ansible](http://www.ansible.com) role which:
>
> * installs ssmtp
> * configures ssmtp
> * manages
> * configures service

## Installation

Using `ansible-galaxy`:

```
$ ansible-galaxy install franklinkim.ssmtp
```

Using `requirements.yml`:

```
- src: franklinkim.ssmtp
```

Using `git`:

```
$ git clone https://github.com/weareinteractive/ansible-ssmtp.git franklinkim.ssmtp
```

## Dependencies

* Ansible >= 1.9

## Variables

Here is a list of all the default variables for this role, which are also available in `defaults/main.yml`.

```
# package name (version)
ssmtp_package: ssmtp
# The person who gets all mail for userids < 1000
# Make this empty to disable rewriting.
ssmtp_root: postmaster
# The place where the mail goes. The actual machine name is required no
# MX records are consulted. Commonly mailhosts are named mail.domain.com
ssmtp_mailhub: mail
# Where will the mail seem to come from?
ssmtp_rewrite_domain:
# The full hostname (defaults to ansible_fqdn)
hostname:
# Are users allowed to set their own From: address?
# YES - Allow the user to specify their own From: address
# NO - Use the system generated From: address
ssmtp_from_line_override: 'YES'
# use TLS
ssmtp_use_starttls: 'YES'
# user name
ssmtp_auth_user:
# password
ssmtp_auth_pass:
```

## Handlers

These are the handlers that are defined in `handlers/main.yml`.

* `restart ssmtp`

## Example playbook

```
- hosts: all
  sudo: yes
  roles:
    - franklinkim.ssmtp
  vars:
    ssmtp_root: admin@example.com
    ssmtp_mailhub: smtp.mandrillapp.com:587
    ssmtp_auth_user: the_mandrill_account_username
    ssmtp_auth_pass: the_mandrill_api_key
```

## Testing

```
$ git clone https://github.com/weareinteractive/ansible-ssmtp.git
$ cd ansible-ssmtp
$ vagrant up
```

## Contributing
In lieu of a formal styleguide, take care to maintain the existing coding style. Add unit tests and examples for any new or changed functionality.

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

## License
Copyright (c) We Are Interactive under the MIT license.
