class Bot < ActiveRecord::Base
  attr_accessible :estado, :nombre, :tw_cuenta, :tw_secret, :tw_token, :siguiendo, :seguidores, :cantidad_seguir, :palabra_indice, :palabra_maximo, :ciudad_indice, :verificar_seguido, :followers_count
  has_many :palabras
  has_many :botCiudads
  has_many :tweets
end
