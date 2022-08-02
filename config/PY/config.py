from distutils.command.config import config
from distutils.debug import DEBUG

#Creación de la clase con Debug activo
class DevelopmentConfig():
    DEBUG = True

#Diccionario
config={
    'development': DevelopmentConfig
}