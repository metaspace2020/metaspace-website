from fabric.decorators import task, roles
from fabric.colors import green
from fabric.api import run, local, env, hosts, sudo
from fabric.contrib.project import rsync_project, upload_project


env.roledefs = {
	'prod': ['intsco@alexandrov-vm01.embl.de']
}


@roles('prod')
def deploy():
    print green('========= Code deployment to the hosting provider server =========')
    #rsync_project(local_dir='./www/', remote_dir='/var/www/html/', extra_opts='-O')
    upload_project(local_dir='./www', remote_dir='/var/www', use_sudo=True)
    sudo('mv /var/www/www /var/www/html')
    sudo('sudo chown -R apache:apache /var/www/html')
