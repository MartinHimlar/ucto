<?php
namespace App;

class InvalidImplementationException extends \RuntimeException
{}

class NotSupportedException extends \RuntimeException
{}

class NotSupportedImageSizeException extends  \RuntimeException
{}

// ---- USERS ----
class UserNotFoundException extends \RuntimeException
{}

class InvalidPermissionException extends \RuntimeException
{}

class AlreadyUserExistException extends \RuntimeException
{}

// ---- CATEGORY ----
class CategoryNotFound extends \RuntimeException
{}

class CategoryAlreadyExistException extends \RuntimeException
{}

class PDFNotFoundException extends \RuntimeException
{}

// ---- SITES ----
class SiteNotAddedException extends \RuntimeException
{}

class SiteNotFoundException extends \RuntimeException
{}

class CarouselNotFoundException extends \RuntimeException
{}

class CarouselNotRemoveException extends \RuntimeException
{}

class CarouselExistException extends \RuntimeException
{}
