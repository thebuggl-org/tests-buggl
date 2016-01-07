#!/bin/bash
printf "update from NAS..\n"
rsync -va /Volumes/TechTeamStore/Buggl/uploads web/
printf "update to NAS...\n"
rsync -va web/uploads /Volumes/TechTeamStore/Buggl