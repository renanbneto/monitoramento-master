#!/bin/bash

PORTA=`cat init.run`
kill -9 $(lsof -t -i:$PORTA)