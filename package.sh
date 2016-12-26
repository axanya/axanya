#!/usr/bin/env bash

CUR_DIR=$PWD
PAR_DIR="$(dirname "$CUR_DIR")"
DIS_DIR="${PAR_DIR}/Makent_Packages/${2}"

mkdir "${DIS_DIR}"

FINAL_DIR="${DIS_DIR}/makent"

gulp compress

find "app/" -type f -exec sed -i '' "s/rsion     ${1}/rsion     ${2}/g" {} \;
find "readme/" -type f -exec sed -i '' "s/Version ${1}/Version ${2}/g" {} \;

cp -R "${CUR_DIR}" "${DIS_DIR}"

sudo rm -r "${FINAL_DIR}/node_modules" "${FINAL_DIR}/.git" "${FINAL_DIR}/.gitignore" "${FINAL_DIR}/.gitattributes" "${FINAL_DIR}/.project" "${FINAL_DIR}/.idea" "${FINAL_DIR}/license.doc" "${FINAL_DIR}/storage/installed" "${FINAL_DIR}/storage/framework/views/"* "${FINAL_DIR}/storage/framework/cache/"* "${FINAL_DIR}/storage/logs/"* "${FINAL_DIR}/.env" "${FINAL_DIR}/js" "${FINAL_DIR}/css" "${FINAL_DIR}/admin_assets/dist/js" "${FINAL_DIR}/admin_assets/dist/css" "${FINAL_DIR}/database/seeds" "${FINAL_DIR}/package.sh"

cp "${FINAL_DIR}/.env.example" "${FINAL_DIR}/.env"

mv "${FINAL_DIR}/min_js" "${FINAL_DIR}/js"

mv "${FINAL_DIR}/min_css" "${FINAL_DIR}/css"

mv "${FINAL_DIR}/admin_assets/dist/min_js" "${FINAL_DIR}/admin_assets/dist/js"

mv "${FINAL_DIR}/admin_assets/dist/min_css" "${FINAL_DIR}/admin_assets/dist/css"

mv "${FINAL_DIR}/database/seeds_package" "${FINAL_DIR}/database/seeds"

rm -r "${FINAL_DIR}/images/rooms/"*/ "${FINAL_DIR}/images/users/"*/

sed -i -e '58d;59d' "${FINAL_DIR}/config/database.php"

LIVE_DEMO_LINE_START=$(grep -n "Live Demo Refresh" "${FINAL_DIR}/resources/views/common/header.blade.php" |cut -f1 -d:)
LIVE_DEMO_LINE_END=$((LIVE_DEMO_LINE_START + 5))

sed -i -e "${LIVE_DEMO_LINE_START},${LIVE_DEMO_LINE_END}d" "${FINAL_DIR}/resources/views/common/header.blade.php"

cp -r "${FINAL_DIR}" "${DIS_DIR}/Makent-Free-${2}"
cp -r "${FINAL_DIR}" "${DIS_DIR}/Makent-Professional-${2}"
cp -r "${FINAL_DIR}" "${DIS_DIR}/Makent-Enterprise-${2}"

sed -i -e '21d' "${DIS_DIR}/Makent-Enterprise-${2}/config/installer.php"

"/Applications/ionCube PHP Encoder.app/Contents/MacOS/ioncube_encoder56_9.0" "${DIS_DIR}/Makent-Free-${2}/app" -o "${DIS_DIR}/Makent-Free-${2}/app_encode" --callback-file "${DIS_DIR}/Makent-Free-${2}/app/Http/Controllers/IonCubeCallback.php" --with-license license.txt --passphrase "${3}" --add-comment "Copyright Trioangle Technologies Pvt Ltd 2016" --add-comment "All Rights Reserved" --ignore "${DIS_DIR}/Makent-Free-${2}/app/Http/Controllers/IonCubeCallback.php"

cp "${DIS_DIR}/Makent-Free-${2}/app/Http/Controllers/IonCubeCallback.php" "${DIS_DIR}/Makent-Free-${2}/app_encode/Http/Controllers/IonCubeCallback.php"

cp "${DIS_DIR}/Makent-Free-${2}/app/Http/Controllers/EmailController.php" "${DIS_DIR}/Makent-Free-${2}/app_encode/Http/Controllers/EmailController.php" 

rm -r "${DIS_DIR}/Makent-Free-${2}/app"

mv "${DIS_DIR}/Makent-Free-${2}/app_encode" "${DIS_DIR}/Makent-Free-${2}/app"

"/Applications/ionCube PHP Encoder.app/Contents/MacOS/ioncube_encoder56_9.0" "${DIS_DIR}/Makent-Professional-${2}/app/Http/Controllers/Admin" -o "${DIS_DIR}/Makent-Professional-${2}/app/Http/Controllers/Admin_encode" --callback-file "${DIS_DIR}/Makent-Professional-${2}/app/Http/Controllers/IonCubeCallback.php" --with-license license.txt --passphrase "${3}" --add-comment "Copyright Trioangle Technologies Pvt Ltd 2016" --add-comment "All Rights Reserved"

rm -r "${DIS_DIR}/Makent-Professional-${2}/app/Http/Controllers/Admin"

mv "${DIS_DIR}/Makent-Professional-${2}/app/Http/Controllers/Admin_encode" "${DIS_DIR}/Makent-Professional-${2}/app/Http/Controllers/Admin"

cp -r "${DIS_DIR}/Makent-Free-${2}" "${DIS_DIR}/free"
cp -r "${DIS_DIR}/Makent-Professional-${2}" "${DIS_DIR}/pro"
cp -r "${DIS_DIR}/Makent-Enterprise-${2}" "${DIS_DIR}/enter"

zip -r "${DIS_DIR}/Makent-Free-${2}.zip" "${DIS_DIR}/Makent-Free-${2}"
zip -r "${DIS_DIR}/Makent-Professional-${2}.zip" "${DIS_DIR}/Makent-Professional-${2}"
zip -r "${DIS_DIR}/Makent-Enterprise-${2}.zip" "${DIS_DIR}/Makent-Enterprise-${2}"
