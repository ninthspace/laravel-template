<?php

function manifest($subdirectory, $filenameWithExtension)
{
    $pathToPublic = public_path($subdirectory.'/');
    $pathToURL = '/'.$subdirectory.'/';

    //    if (php_sapi_name() === 'cli') {
    //        $pathToPublic = TGS_ROOT.$pathToPublic;
    //    }
    $manifestFilenameWithExtension = null;
    $manifest = file_get_contents("{$pathToPublic}manifest.json");
    if ($manifest) {
        $manifestStructure = json_decode($manifest, true);

        if (array_key_exists($filenameWithExtension, $manifestStructure)) {
            $candidateManifestFilename = $manifestStructure[$filenameWithExtension];
            $manifestFilenameWithExtension = $candidateManifestFilename;
        }
    } else {
        $manifestFilenameWithExtension = $filenameWithExtension;
    }

    if ($manifestFilenameWithExtension) {
        return $pathToURL.$manifestFilenameWithExtension;
    }

    return '';
}
