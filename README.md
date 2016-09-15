# mediawiki4intranet-vagrant-ansible
Deploy testing and production environment of MediaWiki4IntraNet with Ansible (and Vagrant)

MediaWiki is no more pure PHP-project suitable for shared hosting:
* WYSIWYG-editor VisualEditor should be supported by NodeJS service [Parsoid](https://www.mediawiki.org/wiki/Parsoid)
* FullText search by Sphinx wait for some magic on VPS.
* Looks like new [collaborative editing](https://sagarhani.wordpress.com/2016/07/31/one-last-step-for-completion-of-google-summer-of-code-kde-wikitolearn/) coming.

VPS hosting [prices dropped](http://lowendstock.com/), so there is no problem to use local VM for debugging
and VPS for deploy.

Here is base project to create and provision local VM with MediaWiki4IntraNet using Vagrant+Ansible
and deploy it to production VPS using Ansible.
(Most modern trend is Docker™ and you may be interested in our [MediaWiki4Intranet Docker Bundle](https://github.com/mediawiki4intranet/docker))

So, lets begin.

If you have some good Linux on development box, please, install:
* [Vagrant](https://www.vagrantup.com/): by package manager or RPM/DEB from the site.
* [Ansible](https://www.ansible.com/): by package manager or by `pip install ansible`.

* [VirtualBox](https://www.virtualbox.org/): by package manager or RPM/DEB from the site.


If you have Windows-box, [you can also try vagrant+ansible](http://discopal.ispras.ru/How_to_use_Ansible_and_Vagrant_for_Windows).

After installing all these stuff:

    git clone git@github.com:mediawiki4intranet/mediawiki4intranet-vagrant-ansible.git
    cd mediawiki4intranet-vagrant-ansible
    vagrant up


While deploying, add to `/etc/hosts`

     127.0.0.1 intrawiki.local.com


All should be OK, like that

    PLAY RECAP
    ********************************************************************* 
    intrawiki                  : ok=468  changed=132  unreachable=0    failed=0


Ask vagrant about port forwarding
    
     vagrant port                                                        

should be like that:
  
     22 (guest) => 2222 (host)               
     80 (guest) => 15304 (host)                
     3306 (guest) => 15305 (host)

----

So you can
* http://intrawiki.local.com:15304/ — and login by «WikiAdmin» with pass «Wiki1729Admin»
* SSH into local VM:
   

    ssh root@0 -p 2222

or simply

     vagrant ssh

* Mount root filesystem of your VM like that


      sshfs root@0:/ -p 2222 -o reconnect /mnt/intrawiki


* You can debug PHP extensions with local IDE listening dbgp-protocol on 9000/tcp. (I checked this with Komodo IDE).


---- 

For production deploy you should edit [hosts.ini](https://github.com/mediawiki4intranet/mediawiki4intranet-vagrant-ansible/blob/master/hosts.ini), setting IP address of your VPS with fresh Centos 7, domain adn site name, and run `!intrawiki-production`


----

Some details: 

* [Vagrantfile](Vagrantfile): Local VM settings. No need to change. Possible to make shared folders, disable debugging, tune CPU and memory usage.

* [hosts.ini](hosts.ini):  How to deploy to production. Do not forget to change WikiAdmin password, or brute russian hackers will hack you.

* [group_vars/all](group_vars/all): Some settings/constants, for example, you can disable fat TexLive installation by «<tt>tex: no</tt>» if you need no LaTeX, or «<tt>wysiwyg: no</tt>», if you need no WYSIWYG and no nodejs stack.

* `roles`:   Ansible roles.
 * [roles/common-root](roles/common-root): Common tuning of minimal Centos 7. Also put your public keys to authorization keys of the VM. (we will touch you  <tt>~/.ssh/id_rsa</tt>, but only for generating public keys).

 * [roles/parsoid](roles/parsoid): NodeJS-based parsing service.

 * [roles/wiki-root-common](roles/wiki-root-common): PHP-NGINX-PHP-FPM stack, common for several wikis.
 * [roles/intrawiki-root](roles/intrawiki-root): Deploying specific wiki instance. copy-and-patch this directory to get several wikis on the VM.
 * [roles/intrawiki-root/vars/main.yml](roles/intrawiki-root/vars/main.yml): Here you can tune extensions list. All checkouted extensions automatically included in LocalSettings.


          - {dest: '',         url: '{{prefix_github}}mediawiki4intranet/core',     version: mediawiki4intranet-core-1.26}
          - {dest: 'config',   url: '{{prefix_github}}mediawiki4intranet/configs',  version: master}
          - {dest: 'vendor',   url: '{{prefix_wikimedia}}vendor',  version: REL1_26}
          - {dest: 'extensions/googleAnalytics',   url: '{{prefix_wikimedia}}extensions/googleAnalytics',  version: master}
          - {dest: 'extensions/MediaFunctions',    url: '{{prefix_wikimedia}}extensions/MediaFunctions',  version: master}
                …
          - {dest: 'skins/cleanmonobook',  url: '{{prefix_github}}mediawiki4intranet/skins-cleanmonobook',  version: master}
          - {dest: 'skins/vector',  url: '{{prefix_github}}mediawiki4intranet/Vector',  version: mw4i-1.26}
          - {dest: 'skins/monobook',  url: '{{prefix_github}}mediawiki4intranet/skins-monobook',  version: mw4i-1.26}

 * [roles/intrawiki-root/templates/LocalSettings.php](roles/intrawiki-root/templates/LocalSettings.php): Detailed wiki tuning for you wiki.


