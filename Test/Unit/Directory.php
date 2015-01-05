<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2015, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Iterator\Test\Unit;

use Hoa\Test;
use Hoa\Iterator as LUT;

/**
 * Class \Hoa\Iterator\Test\Unit\Directory.
 *
 * Test suite of the directory iterator.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2015 Ivan Enderlin.
 * @license    New BSD License
 */

class Directory extends Test\Unit\Suite {

    public function case_classic ( ) {

        $this
            ->given(
                $root = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/A'),
                resolve('hoa://Test/Vfs/Root/Aa'),
                resolve('hoa://Test/Vfs/Root/Aaa'),
                $iterator = new LUT\Directory($root),
                $result   = []
            )
            ->when(function ( ) use ( $iterator, &$result ) {

                foreach($iterator as $key => $file) {

                    $result[$key] = $file->getFilename();

                    $this
                        ->object($file)
                            ->isInstanceOf('Hoa\Iterator\Directory');
                }
            })
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => 'A',
                        1 => 'Aa',
                        2 => 'Aaa'
                    ]);
    }

    public function case_seek_and_dots ( ) {

        $this
            ->given(
                $root = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/.'),
                resolve('hoa://Test/Vfs/Root/..'),
                resolve('hoa://Test/Vfs/Root/Skip'),
                resolve('hoa://Test/Vfs/Root/Gotcha'),
                $iterator = new LUT\Directory($root)
            )
            ->when($result = $iterator->current())
            ->then
                ->boolean($result->isDot())
                    ->isTrue()

            ->when(
                $iterator->next(),
                $result = $iterator->current()
            )
            ->then
                ->boolean($result->isDot())
                    ->isTrue()

            ->when(
                $iterator->seek(3),
                $result = $iterator->current()
            )
            ->then
                ->string($result->getFilename())
                    ->isEqualTo('Gotcha')

            ->when(
                $iterator->seek(2),
                $result = $iterator->current()
            )
            ->then
                ->string($result->getFilename())
                    ->isEqualTo('Skip');
    }

    public function case_recursive ( ) {

        $this
            ->given(
                $root = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/A'),
                resolve('hoa://Test/Vfs/Root/Aa'),
                resolve('hoa://Test/Vfs/Root/Aaa'),
                resolve('hoa://Test/Vfs/Root/Foo?type=directory'),
                resolve('hoa://Test/Vfs/Root/Foo/Bar?type=directory'),
                resolve('hoa://Test/Vfs/Root/Foo/Bar/B'),
                resolve('hoa://Test/Vfs/Root/Foo/Bar/Bb'),
                resolve('hoa://Test/Vfs/Root/Foo/Bar/Bbb'),
                resolve('hoa://Test/Vfs/Root/Foo/C'),
                resolve('hoa://Test/Vfs/Root/Foo/Cc'),
                resolve('hoa://Test/Vfs/Root/Foo/Ccc'),
                $directory = new LUT\Recursive\Directory($root),
                $iterator  = new LUT\Recursive\Iterator($directory),
                $result    = []
            )
            ->when(function ( ) use ( $iterator, &$result ) {

                foreach($iterator as $file)
                    $result[] = $file->getFilename();
            })
            ->then
                ->array($result)
                    ->isEqualTo([
                        'A',
                        'Aa',
                        'Aaa',
                        'B',
                        'Bb',
                        'Bbb',
                        'C',
                        'Cc',
                        'Ccc'
                    ]);
    }

    public function case_splFileClassInfo ( ) {

        $this
            ->given(
                $splFileInfo = 'Hoa\Iterator\SplFileInfo',
                $root        = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/a'),
                resolve('hoa://Test/Vfs/Root/b'),
                resolve('hoa://Test/Vfs/Root/c'),
                resolve('hoa://Test/Vfs/Root/d'),
                resolve('hoa://Test/Vfs/Root/e'),
                resolve('hoa://Test/Vfs/Root/f'),
                $iterator = new LUT\Directory(
                    $root,
                    $splFileInfo
                ),
                $result   = []
            )
            ->when(function ( ) use ( $iterator, $splFileInfo, &$result ) {

                foreach($iterator as $file) {

                    $this
                        ->object($file)
                            ->isInstanceOf($splFileInfo);

                    $result[] = $file->getFilename();
                }
            })
            ->then
                ->array($result)
                    ->isEqualTo([
                        'a',
                        'b',
                        'c',
                        'd',
                        'e',
                        'f'
                    ]);
    }

    public function case_recursive_splFileClassInfo ( ) {

        $this
            ->given(
                $splFileInfo = 'Hoa\Iterator\SplFileInfo',
                $root        = resolve('hoa://Test/Vfs/Root?type=directory'),
                resolve('hoa://Test/Vfs/Root/A?type=directory'),
                resolve('hoa://Test/Vfs/Root/A/a'),
                resolve('hoa://Test/Vfs/Root/A/b'),
                resolve('hoa://Test/Vfs/Root/A/c'),
                resolve('hoa://Test/Vfs/Root/B?type=directory'),
                resolve('hoa://Test/Vfs/Root/B/d'),
                resolve('hoa://Test/Vfs/Root/B/e'),
                resolve('hoa://Test/Vfs/Root/B/c?type=directory'),
                resolve('hoa://Test/Vfs/Root/B/c/f'),
                $directory = new LUT\Recursive\Directory(
                    $root,
                    LUT\FileSystem::CURRENT_AS_FILEINFO,
                    $splFileInfo
                ),
                $iterator  = new LUT\Recursive\Iterator($directory),
                $result    = []
            )
            ->when(function ( ) use ( $iterator, $splFileInfo, &$result ) {

                foreach($iterator as $file) {

                    $this
                        ->object($file)
                            ->isInstanceOf($splFileInfo);

                    $result[] = $file->getFilename();
                }
            })
            ->then
                ->array($result)
                    ->isEqualTo([
                        'a',
                        'b',
                        'c',
                        'd',
                        'e',
                        'f'
                    ]);
    }
}
