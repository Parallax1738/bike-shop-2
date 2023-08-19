#!/bin/bash

color_echo() {
  COLOR=$1
  TEXT=$2
  echo -e "\033[${COLOR}m${TEXT}\033[0m"
}

loading_animation() {
  echo -e -n "\033[33m Installing"
  for i in {1..5}; do
    echo -n "."
    sleep 0.5
  done
  echo -e "\033[0m"
}


echo
echo "Checking if Tailwind CLI is installed..."

if [ -f ./tailwindcss ] && [ ! -x ./tailwindcss ]; then
  chmod +x ./tailwindcss
elif [ -f ./tailwindcss ] && [ -x ./tailwindcss ]; then
  echo
  color_echo "32" "Tailwind CLI is already installed."
elif ! command -v ./tailwindcss &> /dev/null; then
  echo
  color_echo "33" "Tailwind CLI is not installed."
  loading_animation
  curl -sLO https://github.com/tailwindlabs/tailwindcss/releases/download/v3.3.3/tailwindcss-linux-x64
  chmod +x tailwindcss-linux-x64
  mv tailwindcss-linux-x64 tailwindcss
  echo
  color_echo "32" "Tailwind CLI installed successfully!"
fi

echo
echo "Tailwind is watching for changes..."

./tailwindcss -i ./input.css -o ./output.css --watch
