import pandas as pd
import matplotlib.pyplot as plt
import numpy as np
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import StandardScaler
from sklearn.impute import SimpleImputer
from sklearn import preprocessing
from sklearn.linear_model import LinearRegression
from sklearn.tree import DecisionTreeRegressor
from sklearn.metrics import mean_squared_error
from sklearn.ensemble import RandomForestRegressor
from sklearn.neural_network import MLPRegressor
from sklearn.neighbors import KNeighborsRegressor


# séparation du fichier en un trainset et testset


def splitTrainTest(data, testRatio):
    np.random.seed(42)
    shuffledIndices = np.random.permutation(len(data))
    testSetSize = int(len(data) * testRatio)
    testIndices = shuffledIndices[:testSetSize]
    trainIndices = shuffledIndices[testSetSize:]
    return data.iloc[trainIndices], data.iloc[testIndices]


if __name__ == '__main__':
    data = pd.read_csv("test5.csv")
    #TODO : supprimer les lignes invalides (energie à 0, probablement pbm de capture)
    lines1 = len(data)
    data = data[data["ENERGIA1"]>0.]
    lines2 = len(data)
    print(str(lines1-lines2)+" lignes supprimées")

    corr_matrix = data.corr()
    print(corr_matrix["HOUR"].sort_values(ascending=False))

    data = data.drop("ENERGIA2", axis=1)
    data = data.drop("YEAR", axis=1)
    #data = data.drop("MONTH", axis=1)
    data = data.drop("DAY", axis=1)
    #data = data.drop("HOUR", axis=1)
    data = data.drop("UMIDADE", axis=1)
    #data = data.drop("CHUVA", axis=1)

    # Séparation des données d'entrainements et de test
    train_set, test_set = splitTrainTest(data, 0.1)



    # On enlève le champ énergie pour ne pas le prendre en compte dans le training
    train_data = train_set.drop("ENERGIA1", axis=1)

    test_set = test_set.sort_values(by=['HOUR'])
    test_data = test_set.drop("ENERGIA1", axis=1)
    test_labels = test_set["ENERGIA1"].copy()
    train_labels = train_set["ENERGIA1"].copy()


    # Regression linéaire
    #lin_reg = LinearRegression(normalize=False)
    #lin_reg.fit(train_data, train_labels)
    #predictions = lin_reg.predict(test_data)

    # Regression linéaire (KN)
    #lin_reg = KNeighborsRegressor(n_neighbors=70)
    #lin_reg.fit(train_data, train_labels)
    #predictions = lin_reg.predict(test_data)


    # Arbre de décision
    #tree = DecisionTreeRegressor()
    #tree.fit(train_data, train_labels)
    #predictions = tree.predict(test_data)

    # Foret d'arbres de décision
    #forest = RandomForestRegressor(oob_score=True)
    #forest.fit(train_data, train_labels)
    #predictions = forest.predict(test_data)

    # Réseau de neurones
    mlp = MLPRegressor(solver="adam", max_iter=800, hidden_layer_sizes=(5, 7, 7, 5), alpha=0.5)
    mlp.fit(train_data, train_labels)
    predictions = mlp.predict(test_data)


    indexes = [i for i in range(0, len(predictions))]
    plt.plot(indexes, predictions, 'bx', indexes, test_labels, "rx")

    print(predictions)
    print(test_set)
    plt.show()
    rmse = np.sqrt(mean_squared_error(predictions, test_labels))
    print("Erreur RMSE : "+str(rmse))
