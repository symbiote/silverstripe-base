
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
