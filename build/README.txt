
Requirments
----------------------

# Phing
# php cli
# PEAR (with a version of phpunit supported by silverstripe)

Installation
----------------------

# Edit scripts/build.xml and change the <project> name attribute to <projectname>
# Copy scripts/build.properties.sample to scripts/build.properties
# Edit build.properties and change the DB configuration and rewrite.base settings.
# Run `phing -f scripts/build.xml` to make sure everything installs
# If you require additional modules, add them into the scripts/dependent-modules file, then run `phing -f scripts/build.xml update_modules`
# Run `phing -f scripts/build.xml test` to make sure everything's working as expected

Optional Scripts
----------------------

There are two scripts that may optionally be used for your projects, and can be done using the following commands.

# sh ~/path/to/permissions

This requires you to update the "user" and as such will need to be copied out to a location of your choice, and will apply the appropriate owner and permissions to both the cache and repository.

# sh build/scripts/recursive-status

This will recursively trigger a "git status" on each module directory found within your repository, primarily so you can gitignore any modules from the repository code, yet still check for changes that may have been made.
