#!/usr/bin/env bash
set -euo pipefail

# Regenerate the API client from Convoy's OpenAPI spec with OpenAPI Generator
# (php), then sync it into src/Client/ without touching the hand-written SDK
# code (the rest of src/, incl. webhook verify).
#
# Requires: java 17+, rsync, curl. Run from the repo root.

SPEC_URL="${SPEC_URL:-https://raw.githubusercontent.com/frain-dev/convoy/main/docs/v3/openapi3.yaml}"
# Pin so regeneration output is reproducible; bump deliberately.
GENERATOR_VERSION="7.23.0"
GENERATOR_JAR="${GENERATOR_JAR:-.cache/openapi-generator-cli-${GENERATOR_VERSION}.jar}"
# Official artifact checksum; the download is verified before execution so a
# compromised mirror/CDN cannot run arbitrary code in CI. Update alongside
# GENERATOR_VERSION (sha256 of the Maven Central JAR).
GENERATOR_SHA256="cb087e40001e31eb08ef6140dd5de10938dbeb89016a1fe0481eaa25cd569026"

tmp="$(mktemp -d)"
trap 'rm -rf "$tmp"' EXIT

if [ ! -f "$GENERATOR_JAR" ]; then
  mkdir -p "$(dirname "$GENERATOR_JAR")"
  curl -fsSL -o "$GENERATOR_JAR" \
    "https://repo1.maven.org/maven2/org/openapitools/openapi-generator-cli/${GENERATOR_VERSION}/openapi-generator-cli-${GENERATOR_VERSION}.jar"
fi

# Fail closed on checksum mismatch (covers cached files too).
echo "${GENERATOR_SHA256}  ${GENERATOR_JAR}" | shasum -a 256 -c - >/dev/null || {
  echo "ERROR: ${GENERATOR_JAR} failed sha256 verification" >&2
  exit 1
}

curl -fsSL "$SPEC_URL" -o "$tmp/openapi3.yaml"

java -jar "$GENERATOR_JAR" generate \
  -i "$tmp/openapi3.yaml" \
  -g php \
  -c .openapi-generator-config.yaml \
  -o "$tmp/gen"

# Mirror only the generated namespace. --delete keeps src/Client an exact
# mirror of generator output; the hand-written src/ code is never touched.
rsync -a --delete "$tmp/gen/lib/" src/Client/

echo "Generated client synced into src/Client/"
