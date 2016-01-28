<?php

/**
* Функция для копирования, обрезания картинок
*
* @param    string  $in_file Путь до входящего файла
* @param    string  $out_file Путь до исходящего (нового) файла
* @param    string  $type_file mime-тип файла или на худой конец расширение
* @param    string  $type_resize Тип обрезания
* @param    int  $width_new Ширина нового изображения
* @param    int  $height_new Высота нового изображения
* @return   void
*/

// Images types
define( 'CFG_IMAGE_RESIZE_TYPE_0', 0 ); //Тип 0: Обычное копирование
define( 'CFG_IMAGE_RESIZE_TYPE_1', 1 ); //Тип 1: Сжатие по ширине картинки. Высота остаётся неизменной
define( 'CFG_IMAGE_RESIZE_TYPE_2', 2 ); //Тип 2: Сжатие по высоте картинки. Ширина остаётся неизменной
define( 'CFG_IMAGE_RESIZE_TYPE_3', 3 ); //Тип 3: Устанавливаются новые значения, пропорции не сохраняются
define( 'CFG_IMAGE_RESIZE_TYPE_4', 4 ); //Тип 4: Вставляем изображение в прямоугольник
define( 'CFG_IMAGE_RESIZE_TYPE_5', 5 ); //Тип 5: Сжатие по наибольшему значению
define( 'CFG_IMAGE_RESIZE_TYPE_6', 6 ); //Тип 6: Вставляем изображение в прямоугольник с обрезанием краёв

function resizeImage($in_file, $out_file, $type_file, $type_resize = false, $width_new = false, $height_new = false, $color = false)
{
    $image_new = false;
            
    // Если такой файл уже есть, удаляем его
    if(is_file($out_file))
    {
        chmod($out_file, 0777);
        unlink($out_file);
    }

    $type_file = strtolower($type_file);

    // Считываем старую картинку
    switch($type_file)
    {
       case 'image/jpeg':
       case 'jpg':
       case 'jpeg':
           $image = imagecreatefromjpeg( $in_file );
           break;
       case 'image/png':
       case 'png':
           $image = imagecreatefrompng( $in_file );
           break;
       case 'image/gif':
       case 'gif':
           $image = imagecreatefromgif( $in_file );
           break;
       default:
           return array('error' => 1, 'message' => 'Не верный тип изображения');
    }

    // Определяем высоту и ширину старую картинки
    $width_old = imagesx( $image );
    $height_old = imagesy( $image );

    // Впихиваем изображение в прямоугольник
    if($type_resize == CFG_IMAGE_RESIZE_TYPE_4 || $type_resize == CFG_IMAGE_RESIZE_TYPE_6)
    {
        if ( !$width_new || !$height_new )
            return array('error' => 1, 'message' => 'Не задана ширина и(или) высота нового изображения');

        $restagle[0] = $width_new;
        $restagle[1] = $height_new;

        if($type_resize == CFG_IMAGE_RESIZE_TYPE_4)
        {
            if( ( $width_old / $height_old ) > ( $width_new / $height_new ) )
            {
                $type_resize = 1;
                $size = $width_new;
            }
            else
            {
                $type_resize = 2;
                $size = $height_new;
            }
            
            $clipping = true;
        }
      
        if($type_resize == CFG_IMAGE_RESIZE_TYPE_6)
        {
            if( ( $width_old / $height_old ) > ( $width_new / $height_new ) )
            {
                $type_resize = 2;
                $size = $width_new;
            }
            else
            {
                $type_resize = 1;
                $size = $height_new;
            }
            
            $clipping2 = true;
        }
    }

    // Уменьшаем по наибольшему значению
    if($type_resize == CFG_IMAGE_RESIZE_TYPE_5)
    {
        if($width_old > $height_old)
            $type_resize = 2;
        else
            $type_resize = 1;
    }
    
    switch($type_resize)
    {
        case CFG_IMAGE_RESIZE_TYPE_1:
                { // Обрезание по ширине картинки. Высота остаётся неизменной
                    if(!$width_new)
                        return array('error' => 1, 'message' => 'Не задана ширина нового изображения');

                    if ( $width_new < $width_old )
                        $height_new = round( ($height_old * $width_new) / $width_old );
                    else
                        $height_new = $height_old;

                    break;
                }
        case CFG_IMAGE_RESIZE_TYPE_2:
                { // Обрезание по высоте картинки. Ширина остаётся неизменной
                    if(!$height_new)
                        return array('error' => 1, 'message' => 'Не задана высота нового изображения');

                    if ( $height_new < $height_old )
                        $width_new = round( ($width_old * $height_new) / $height_old );
                    else
                        $width_new = $width_old;
                    
                    break;
                }
        case CFG_IMAGE_RESIZE_TYPE_3: // Устанавливаются новые значения, пропорции не сохраняются
                break;

        default:  // Если не указан тип преобразования, просто копируем файл
                return copy($in_file, $out_file);
    }

    // Преобразуем старое изображение в новое
    $image_new = imagecreatetruecolor( $width_new, $height_new );
    imagecopyresampled( $image_new , $image, 0, 0, 0, 0, $width_new, $height_new, $width_old, $height_old );

    // Для типа 4, впихиваем изображение в прямоугольник с заданием фона
    if(@$clipping)
    {
        // Цвет фона
        if($color)
            $rgb_color = HEXtoRGB($color);
        else
            $rgb_color = array('255', '255', '255');

        if($type_resize == CFG_IMAGE_RESIZE_TYPE_2)
        {
            $dx = ($width_new - $restagle[0]) / 2;
            $image_temp = imagecreatetruecolor( $restagle[0], $restagle[1] );
            $color = imagecolorallocate($image_temp, $rgb_color[0], $rgb_color[1], $rgb_color[2]);
            imagecopy( $image_temp, $image_new, 0, 0, $dx, 0, $restagle[0], $restagle[1] );
            imagefilledrectangle($image_temp, 0, 0, abs($dx), $restagle[1] ,$color);
            imagefilledrectangle($image_temp, $restagle[0]-abs($dx), 0, $restagle[0], $restagle[1] ,$color);

        }

        if($type_resize == CFG_IMAGE_RESIZE_TYPE_1)
        {
            $dy = ($height_new - $restagle[1]) / 2;
            $image_temp = imagecreatetruecolor( $restagle[0], $restagle[1] );
            imagecopy( $image_temp, $image_new, 0, 0, 0, $dy, $restagle[0], $restagle[1] );
            $color = imagecolorallocate($image_temp, $rgb_color[0], $rgb_color[1], $rgb_color[2]);
            imagefilledrectangle($image_temp, 0, 0, $restagle[0], abs($dy) ,$color);
            imagefilledrectangle($image_temp, 0, $restagle[1]-abs($dy), $restagle[0], $restagle[1] ,$color);
        }

        $image_new = $image_temp;
    }
    
    // Для типа 6, впихиваем изображение в прямоугольник с обрезанием краёв
    if(@$clipping2)
    {
        if($type_resize == CFG_IMAGE_RESIZE_TYPE_2)
        {
            $dx = ($width_new - $restagle[0]) / 2;
            $image_temp = imagecreatetruecolor( $restagle[0], $restagle[1] );
            imagecopy( $image_temp, $image_new, 0, 0, $dx, 0, $restagle[0], $restagle[1] );
        }

        if($type_resize == CFG_IMAGE_RESIZE_TYPE_1)
        {
            $dy = ($height_new - $restagle[1]) / 2;
            $image_temp = imagecreatetruecolor( $restagle[0], $restagle[1] );
            imagecopy( $image_temp, $image_new, 0, 0, 0, $dy, $restagle[0], $restagle[1] );
        }
        
        $image_new = $image_temp;
    }

    // Сохраняем изображение
    switch($type_file)
    {
        case 'image/jpeg':
        case 'jpg':
        case 'jpeg':
            $check = imagejpeg( $image_new, $out_file, 100 );
        break;
        case 'image/png':
        case 'png':
            $check = imagepng( $image_new, $out_file, 9 );
        break;
        case 'image/gif':
        case 'gif':
            $check = imagegif( $image_new, $out_file, 100 );
        break;
            default:
        return false;
    }

    // Очищаем переменные
    @imagedestroy( $image );
    @imagedestroy( $image_new );
    @imagedestroy( $image_temp );

    if($check)
        return array('error' => 0, 'message' => 'Изображение сохранено');
    else
        return array('error' => 1, 'message' => 'Изображение не было сохранено');

}


/**
* Функция для перевода цвета из 16-ой системы в RGB
*
* @param    string  Цвет в 16-ой системе, например #CCCCCC или CCCCCC или ccc
* @return   array   RGB
*/
function HEXtoRGB($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

?>