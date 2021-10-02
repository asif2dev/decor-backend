#!/usr/bin/env bash
set -eo pipefail

echo "generating .env file for $ENVIRONMENT"
rm -f .env

while IFS= read -r line; do
    IFS== read -r left right <<< "$line"
    if [[ ${left:0:1} == "#" ]] ; then
      continue
    fi
    if [ -n "$left" ]; then
        env_var_specific="${ENVIRONMENT}_${left}"
        env_var_generic="${left}"
        checkForSpecialCharacters=1
        if [ -n "${!env_var_specific}" ]; then
            # get env variable for current specific env
            value="${!env_var_specific}"
        elif [ -n "${!env_var_generic}" ]; then
            # get env variable from unspecific env
            value="${!env_var_generic}"
        else
            # just use the existing value
            value="$right"
            # existing values will not be modified
            checkForSpecialCharacters=0
        fi
        # check if the value from env contains special characters, if so add quotes
        if [[ -z "${value##*#*}" ]] && [[ ${right:0:1} != "\""  ]] && [[ $checkForSpecialCharacters -eq 1 ]] ; then
            value="\"${value}\""
        else
            value="${value}"
        fi
        if [ -n "$value" ]; then
            printf '%s=%s\n' "${left}" "${value}" >> .env
        fi
    else
        echo "$left" >> .env
    fi
done < .env."${ENVIRONMENT}"
# laravel auto load the .env.environment file, we copy the .env to the .env.environment
cp .env .env."${ENVIRONMENT}"
