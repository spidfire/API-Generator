#API generator

This tool is a easy to use generator for any sourcecode.

## once per file
```
/**
 * @group {name of the group}
 * @version {version of the api}
 * @created {date of creation}
 */
```

# every api function
```
/**
 * @description {the description}
 * @http_method {GET POST PUT DELETE}
 * @url {The url to access this resource}
 * @input_var {variable name} {tags} {tags} [{description}]
 * @get_params {variable name} {tags} {tags} [{description}]
 * @succes {type return}
 * @fail {type return}
 * @auth {type authentication}

*/
```