#!/bin/bash
##################################################################### 
# Minera builder script to build and update miner software binaries
#
# For usage just run the build_miner.sh without any argument
#
# Thanks to @brettvitaz https://github.com/brettvitaz
##################################################################### 
 
set -u
set -e
 
LINK_ONLY=0
BUILD_OK=0
 
MINERA_PATH="/var/www/minera"
BINARY_PATH="$MINERA_PATH/minera-bin"
SOURCE_PATH="$BINARY_PATH/src"
 
CPUMINER_REPO="https://github.com/siklon/cpuminer-gc3355"
CPUMINER_PATH="$SOURCE_PATH/cpuminer-gc3355"
CPUMINER_CONFIG="CFLAGS=-O3"
CPUMINER_BINARY="minerd"
CPUMINER_MINERA_BINARY="minerd"
 
BFGMINER_REPO="https://github.com/luke-jr/bfgminer.git"
BFGMINER_PATH="$SOURCE_PATH/bfgminer"
BFGMINER_CONFIG="--enable-scrypt --enable-broad-udevrules --without-system-libbase58"
BFGMINER_BINARY="bfgminer"
BFGMINER_MINERA_BINARY="bfgminer"
 
CGMINER_DMAXL_REPO="https://github.com/dmaxl/cgminer.git"
CGMINER_DMAXL_PATH="$SOURCE_PATH/cgminer-dmaxl-zeus"
CGMINER_DMAXL_CONFIG="--enable-scrypt --enable-gridseed --enable-zeus"
CGMINER_DMAXL_BINARY="cgminer"
CGMINER_DMAXL_MINERA_BINARY="cgminer-dmaxl-zeus"
 
CGMINER_REPO="https://github.com/ckolivas/cgminer.git"
CGMINER_PATH="$SOURCE_PATH/cgminer"
CGMINER_CONFIG="--enable-avalon2 --enable-bflsc --enable-bitforce --enable-bitfury --enable-drillbit --enable-hashfast --enable-icarus --enable-klondike --enable-modminer"
CGMINER_BINARY="cgminer"
CGMINER_MINERA_BINARY="cgminer"
 
function buildMiner {
	if [[ $LINK_ONLY -eq 0 ]]; then
		if [[ -d "$BUILD_PATH/.git" ]]; then
			cd $BUILD_PATH
			echo "Pulling repo $BUILD_REPO"
			git fetch --all
			git reset --hard
			git pull
		else
			echo "Cloning repo $BUILD_REPO into $BUILD_PATH"
			git clone $BUILD_REPO $BUILD_PATH 
			cd $BUILD_PATH
		fi
		./autogen.sh
		echo "Running ./configure $BUILD_CONFIG"
		./configure ${BUILD_CONFIG}
		make
		sudo make install
		sudo ldconfig
	fi
	if [[ -e "$BUILD_PATH/$BUILD_BINARY" ]]; then
		echo "Removing old binary $BINARY_PATH/$MINERA_BINARY"
		rm $BINARY_PATH/$MINERA_BINARY
		echo "Copying new binary $BUILD_PATH/$BUILD_BINARY -> $BINARY_PATH/$MINERA_BINARY"
		cp $BUILD_PATH/$BUILD_BINARY $BINARY_PATH/$MINERA_BINARY
	else
		echo "Failed to copy miner binary. File $BUILD_PATH/$BUILD_BINARY does not exist."
	fi
}
 
ARGS="${@/%all/cpuminer bfgminer cgminer-dmaxl cgminer}"
 
if [[ -d "$SOURCE_PATH" ]]; then
	for OPT in $ARGS; do
		echo "$OPT"
		if [[ $OPT = "-l" ]]; then
			LINK_ONLY=1
		elif [[ $OPT = "all" ]]; then
			ARGS="cpuminer bfgminer cgminer-dmaxl cgminer"
		elif [[ $OPT = "cpuminer" ]]; then
			BUILD_REPO=$CPUMINER_REPO
			BUILD_PATH=$CPUMINER_PATH
			BUILD_CONFIG=$CPUMINER_CONFIG
			BUILD_BINARY=$CPUMINER_BINARY
			MINERA_BINARY=$CPUMINER_MINERA_BINARY
			BUILD_OK=1
		elif [[ $OPT = "bfgminer" ]]; then
			BUILD_REPO=$BFGMINER_REPO
			BUILD_PATH=$BFGMINER_PATH
			BUILD_CONFIG=$BFGMINER_CONFIG
			BUILD_BINARY=$BFGMINER_BINARY
			MINERA_BINARY=$BFGMINER_MINERA_BINARY
			BUILD_OK=1
		elif [[ $OPT = "cgminer-dmaxl" ]]; then
			BUILD_REPO=$CGMINER_DMAXL_REPO
			BUILD_PATH=$CGMINER_DMAXL_PATH
			BUILD_CONFIG=$CGMINER_DMAXL_CONFIG
			BUILD_BINARY=$CGMINER_DMAXL_BINARY
			MINERA_BINARY=$CGMINER_DMAXL_MINERA_BINARY
			BUILD_OK=1
		elif [[ $OPT = "cgminer" ]]; then
			BUILD_REPO=$CGMINER_REPO
			BUILD_PATH=$CGMINER_PATH
			BUILD_CONFIG=$CGMINER_CONFIG
			BUILD_BINARY=$CGMINER_BINARY
			MINERA_BINARY=$CGMINER_MINERA_BINARY
			BUILD_OK=1
		else
			echo "Option not recognized = $OPT"
			BUILD_OK=0
		fi
		if [[ $BUILD_OK -eq 1 ]]; then
			buildMiner
		fi
	done
	if [[ $BUILD_OK -eq 0 ]]; then
			echo "Usage: build_miner.sh [OPTION] [MINER NAME(S)]..."
			echo ""
			echo "Arguments:"
			echo "  -l                Link binaries only (do not build)"
			echo ""
			echo "Miner Names:"
			echo "  cgminer           cgminer official"
			echo "  cpuminer          cpuminer GC3355 fork"
			echo "  bfgminer          bfgminer official"
			echo "  cgminer-dmaxl     cgminer dmaxl fork (gridseed and zeus support)"
			echo "  all               build all the above"
			echo ""
	fi
else
	echo "Minera source folder does not exist. Please install Minera prior to building the miners."
fi
