<?php

declare(strict_types=1);

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
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

namespace igorora\Iterator\Test\Unit;

use igorora\Iterator as LUT;
use igorora\Protocol;
use igorora\Test;

/**
 * Class \igorora\Iterator\Test\Unit\FileSystem.
 *
 * Test suite of the filesystem iterator.
 *
 * @license    New BSD License
 */
class FileSystem extends Test\Unit\Suite
{
    public function case_classic(): void
    {
        $this
            ->given(
                $root = $this->resolve('igorora://Test/Vfs/Root?type=directory'),
                $this->resolve('igorora://Test/Vfs/Root/.?type=directory'),
                $this->resolve('igorora://Test/Vfs/Root/..?type=directory'),
                $this->resolve('igorora://Test/Vfs/Root/A?type=file'),
                $this->resolve('igorora://Test/Vfs/Root/B?type=file'),
                $iterator = new LUT\FileSystem($root),
                $result   = []
            )
            ->when(function () use ($iterator, &$result): void {
                foreach ($iterator as $pathname => $file) {
                    $this
                        ->object($file)
                            ->isInstanceOf('SplFileInfo');

                    $result[basename($pathname)] = $file->getFilename();
                }
            })
            ->array($result)
                ->isEqualTo([
                    'A' => 'A',
                    'B' => 'B'
                ]);
    }

    public function case_splFileClassInfo(): void
    {
        $this
            ->given(
                $splFileInfo = 'igorora\Iterator\SplFileInfo',
                $root        = $this->resolve('igorora://Test/Vfs/Root?type=directory'),
                $this->resolve('igorora://Test/Vfs/Root/a?type=file'),
                $this->resolve('igorora://Test/Vfs/Root/b?type=file'),
                $this->resolve('igorora://Test/Vfs/Root/c?type=file'),
                $this->resolve('igorora://Test/Vfs/Root/d?type=file'),
                $this->resolve('igorora://Test/Vfs/Root/e?type=file'),
                $this->resolve('igorora://Test/Vfs/Root/f?type=file'),
                $iterator = new LUT\FileSystem(
                    $root,
                    LUT\FileSystem::CURRENT_AS_FILEINFO,
                    $splFileInfo
                ),
                $result   = []
            )
            ->when(function () use ($iterator, $splFileInfo, &$result): void {
                foreach ($iterator as $file) {
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

    private function resolve(string $path)
    {
        return Protocol\Protocol::getInstance()->resolve($path);
    }
}
